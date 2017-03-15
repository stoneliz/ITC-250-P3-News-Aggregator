<?php
/**
 * survey_view.php is a page to demonstrate the proof of concept of the 
 * initial SurveySez objects.
 *
 * Objects in this version are the Survey, Question & Answer objects
 * 
 * @package NewsAggregator
 * @author Group 7 Anthony Stenberg-Di Geronimo, Kiara McMorris, Liz Stone, Yan Men
 * @version 2.10 2017/03/09
 * @link https://github.com/stoneliz/ITC-250-P3-News-Aggregator
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see config_inc.php  
 * @see header_inc.php
 * @see footer_inc.php 
 * @todo none
 */
 
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
spl_autoload_register('MyAutoLoader::NamespaceLoader');//required to load namespace objects
$config->metaRobots = 'no index, no follow';#never index survey pages

# check variable of item passed in - if invalid data, forcibly redirect back to demo_list.php page
if (isset($_GET['id']) && (int)$_GET['id'] > 0){#proper data must be on querystring
	$myID = (int)$_GET['id']; #Convert to integer, will equate to zero if fails
}else {
	myRedirect(VIRTUAL_PATH . "news/index.php");
}
$myNews = new NewsFeedz\News($myID); //mynews extends survey class so methods can be added
if ($myNews->isValid){
	$config->titleTag = "'" . $myNews->Title . "' News feed!";
}else {
	$config->titleTag = smartTitle(); //use constant 
}

if (isset($_GET['id'])){//process data
    //cast the data to an integer, for security purposes
    $id = (int)$_GET['id'];
}

// ** This SQL Variable  Selects from the NewsFeed entity where the News ID matches what the user chose on the news_view page. The ID was passed as a loaded query string and validated by the code above this line.
$sql = "select * from NewsFeed where NewsID=$id";
#IDB::conn() creates a shareable database connection via a singleton class
$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
$row = mysqli_fetch_assoc($result);
#END CONFIG AREA ---------------------------------------------------------- 
get_header(); #defaults to theme header or header_inc.php
?>

<?php
// ** This Echo statement sets up the table. It also includes classes from the rest of the protosite to maintain a similar look and feel. 
echo'<table class="table table-striped table-hover ">
     <thead>
       <tr>
         <th>Title</th>
         <th>Description</th>
       </tr>
     </thead>
     <tbody>';

$request = $row['RSS']; //go news.google.com and get RSS URL topic=tc can change topic to anything =us&q=star+wars&
$response = file_get_contents($request); 
$xml = simplexml_load_string($response);
print '<h1>' . $xml->channel->title . '</h1>';

foreach ($xml->channel->item as $story){
        echo '<tr>';
        echo '<td colspan="2">';
        echo '<a href="' . $story->link . '">' . $story->title . '</a><br />'; 
        echo '<p>' . $story->description . '</p><br /><br />';
        echo '</td>';
        echo '</tr>';
      }
    echo '  </tbody>
            </table> ';
   

get_footer(); #defaults to theme footer or footer_inc.php

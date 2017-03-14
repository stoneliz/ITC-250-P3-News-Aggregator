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
spl_autoload_register('MyAutoLoader::NamespaceLoader');//required to load SurveySez namespace objects
$config->metaRobots = 'no index, no follow';#never index survey pages
# check variable of item passed in - if invalid data, forcibly redirect back to demo_list.php page

if(isset($_GET['id']) && (int)$_GET['id'] > 0){#proper data must be on querystring
	 $myID = (int)$_GET['id']; #Convert to integer, will equate to zero if fails
}else{
	myRedirect(VIRTUAL_PATH . "news/index.php");
}
$myNews = new NewsFeedz\News($myID); //MYNews extends survey class so methods can be added
if($myNews->isValid)
{
	$config->titleTag = "'" . $myNews->Title . "' News feed!";
}else{
	$config->titleTag = smartTitle(); //use constant 
}

if(isset($_GET['id']))
{//process data
    //cast the data to an integer, for security purposes
    $id = (int)$_GET['id'];
}else{//redirect to safe page
    header('Location:index.php');
}

// ** This sql variable pulls the three feeds that matches the CategoryID. The ID was passed as a loaded query string and validated by the code above this line.
$sql = "select * from NewsFeed where CategoryID=$id";
#END CONFIG AREA ---------------------------------------------------------- 
get_header(); #defaults to theme header or header_inc.php
?>
    

<?php
 //  IDB::conn() creates a shareable database connection via a singleton class
 $result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));

 if(mysqli_num_rows($result) > 0)
{#there are records - present data
    // ** This Echo statement sets up the table. It also includes classes from the rest of the protosite to maintain a similar look and feel. 
    echo'<table class="table table-striped table-hover ">
  <thead>
    <tr>
      <th>Title</th>
      <th>Description</th>
    </tr>
  </thead>
  <tbody>';
    // ** This while loop Spits out the rows based on the Variable $result.
	while($row = mysqli_fetch_assoc($result))
	{# pull data from associative array
	  
        echo '<tr>
      <td><a href="feed_view.php?id=' . $row['NewsID'] . '">' . $row['NewsTitle'] . '</a></td>  
      <td>' . $row['Description'] . '</td>
    </tr>';
	}
    // ** This closes the table. 
    echo '  </tbody>
</table> ';
    
}else{#no records
	echo '<div align="center">Sorry, there are no records that match this query</div>';
}





get_footer(); #defaults to theme footer or footer_inc.php

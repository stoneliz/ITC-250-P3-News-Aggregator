<?php
/**
 * survey_view.php is a page to demonstrate the proof of concept of the 
 * initial SurveySez objects.
 *
 * Objects in this version are the Survey, Question & Answer objects
 * 
 * @package SurveySez
 * @author William Newman
 * @version 2.12 2015/06/04
 * @link http://newmanix.com/ 
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @see Question.php
 * @see Answer.php
 * @see Response.php
 * @see Choice.php
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
$myNews = new NewsFeedz\News($myID); //MY_Survey extends survey class so methods can be added
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
}


$sql = "select * from NewsFeed where NewsID=$id";
#END CONFIG AREA ---------------------------------------------------------- 
get_header(); #defaults to theme header or header_inc.php
?>

<?php
 //  This line
 if(mysqli_num_rows($result) > 0)
{#there are records - present data
    
    echo'<table class="table table-striped table-hover ">
  <thead>
    <tr>
      <th>Title</th>
      <th>Description</th>
    </tr>
  </thead>
  <tbody>';
 while($row = mysqli_fetch_assoc($result))
 {

     $request = $row['RSS']; //go news.google.com and get RSS URL topic=tc can change topic to anything =us&q=star+wars&
      $response = file_get_contents($request); 
      $xml = simplexml_load_string($response);
      print '<h1>' . $xml->channel->title . '</h1>';
      foreach($xml->channel->item as $story)
      {
        echo '<a href="' . $story->link . '">' . $story->title . '</a><br />'; 
        echo '<p>' . $story->description . '</p><br /><br />';
      }
  }
    echo '  </tbody>
</table> ';
    
}else{#no records
    echo '<div align="center">Sorry, there are no records that match this query</div>';
}



 if(mysqli_num_rows($result) > 0)
{#there are records - present data
    
    echo'<table class="table table-striped table-hover ">
  <thead>
    <tr>
      <th>Title</th>
      <th>Description</th>
    </tr>
  </thead>
  <tbody>';
    
    while($row = mysqli_fetch_assoc($result))
    {# pull data from associative array
       /*echo '<p>';
       echo 'Title: <b>' . $row['Title'] . '</b><br />';
       echo 'Description: <b>' . $row['Description'] . '</b><br />';
       echo '<a href="survey_view.php?id=' . $row['SurveyID'] . '">' . $row['Title'] . '</a>';    
       echo '</p>';
       */
        echo '<tr>
      <td><a href="' . $row['RSS'] . '">' . $row['NewsTitle'] . '</a></td>  
      <td>' . $row['RSS'] . '</td>
    </tr>';
    }
    
    echo '  </tbody>
</table> ';
    
}else{#no records
    echo '<div align="center">Sorry, there are no records that match this query</div>';
}





get_footer(); #defaults to theme footer or footer_inc.php

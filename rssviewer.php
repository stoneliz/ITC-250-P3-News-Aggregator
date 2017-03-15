<?php
/**
 * news_view.php 
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

session_start();

$FeedID = $_GET['feedid'];

//current time
$currentTime = date("Y-m-d H:i:s");
    
if (!isset($_SESSION['feeds'][$FeedID])){
    //default to a time 15 minutes in the past
    $pastTime = date_sub(new DateTime($currentTime), date_interval_create_from_date_string('15 minutes'));
} else {
      $session = $_SESSION["feeds"];
      $date = new DateTime($session[$FeedID][1]);
      date_add($date, date_interval_create_from_date_string('10 minutes'));
    
      $thePastTime = date_format($date, 'Y-m-d H:i:s');
}
$theTime = date("Y-m-d H:i:s");
echo "Current time: $currentTime <br />";
echo "Next available update: $thePastTime <br />";

//compare current time to time when rss feed can be refreshed
//display new feed

if ($currentTime > $thePastTime) {
    //display refreshed rss feed then update session and db
    $sql1 = "SELECT Feed(orsometthing) FROM tablename WHERE FeedID=$FeedID";
    $connect = @mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    $result = mysqli_query($connect,$sql1) or die(trigger_error(mysqli_error($connect), E_USER_ERROR));
    
    while ($row = mysqli_fetch_assoc($result)) {
        $Feed = $row['Feed'];
    }
    @mysqli_free_result($result);
    @mysqli_close($connect);
    
    $request = "https://news.google.com/news?cf=all&hl=en&pz=1&ned=us&q=".$Feed."&output=rss";
    $response = file_get_contents($request);
    //make xml object
    $xml = simplexml_load_string($response);
    
    echo '<h1>' . $xml->channel->title . '</h1>';
    foreach ($xml->channel->item as $story) {
        echo '<a href="' . $story->link . '">' . $story->title . '</a><br />'; 
        echo '<p>' . $story->description . '</p><br /><br />';
    }
    
    //update session
    $f[$FeedID][0]=$response;
    $f[$FeedID][1]=$currentTime;
    $_SESSION["feeds"]=$f;
    
} else {
    //display old cached feed
      $string = $session[$FeedID][0];
      $xml = simplexml_load_string($string);
    
      echo '<h1>' . $xml->channel->title . '</h1>';
      foreach ($xml->channel->item as $story) {
          echo '<a href="' . $story->link . '">' . $story->title . '</a><br />'; 
          echo '<p>' . $story->description . '</p><br /><br />';
    }
}
?>

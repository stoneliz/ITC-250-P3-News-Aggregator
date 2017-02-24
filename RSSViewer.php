<?php

require 'inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
include 'include/loadRSS.php';
include 'include/NewsItem.php';
$config->titleTag = "RSS Viewer Page"; #Fills <title> tag. If left empty will fallback to $config->titleTag in config_inc.php  
$config->banner = 'RSS Viewer'; #goes inside header
$iConn = @mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die(myerror(__FILE__,__LINE__,mysqli_connect_error())) ;

//NOTE: the name of the table is madeup, please feel free to change it.
$sql = 'select Time_Stamp from wn17_p3_articles limit 1';
$result = mysqli_query($iConn, $sql);
$lastUpdate =  new DateTime();
$numRow = mysqli_num_rows($result);


while($row = $result->fetch_assoc())
{
    $lastUpdate =  new DateTime($row['Time_Stamp']);


}


get_header('SmallPark_AboutUs_header_inc.php'); #defaults to theme header or header_inc.php
$date = new DateTime();

$interval = $date->diff($lastUpdate);
$timeDifference=$interval->format('%i');


//checks age of data, or if database is empty
if(($timeDifference > 10) || $numRow ==0)
{


    $loader = new RSSLoader();
    $loader->reloadRSS();
  

}


//display results
//NOTE: the name of the table is madeup, please feel free to change it.
$sql2 = 'select * from wn17_p3_articles limit 10';
$result2 = mysqli_query($iConn, $sql2);

echo '
<html>
<body>

<table>';

while($row2 = $result2->fetch_assoc())
{

    $item = new NewsItem($row2['Title'], $row2['Description'], $row2['URL'], $row2['OriginalDate'], $row2['ImageURL']);
    $item->display();


}



echo '
</table>
</body>


</html>';


get_footer(); #defaults to theme header or footer_inc.php

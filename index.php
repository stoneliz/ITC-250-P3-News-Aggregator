<?php
/**
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

$config->titleTag = smartTitle(); #Fills <title> tag. If left empty will fallback to $config->titleTag in config_inc.php
$config->metaDescription = smartTitle() . ' - ' . $config->metaDescription; 
/*
$config->metaDescription = ''; #Fills <meta> tags.
$config->metaKeywords = '';
$config->metaRobots = 'no index, no follow';
$config->loadhead = ''; #load page specific JS
$config->banner = ''; #goes inside header
$config->copyright = ''; #goes inside footer
$config->sidebar1 = ''; #goes inside left side of page
$config->sidebar2 = ''; #goes inside right side of page
$config->nav1["page.php"] = "New Page!"; #add a new page to end of nav1 (viewable this page only)!!
$config->nav1 = array("page.php"=>"New Page!") + $config->nav1; #add a new page to beginning of nav1 (viewable this page only)!!
*/

# SQL statement - PREFIX is optional way to distinguish your app
// ** this sets the variable for the sql statement for this page. It pulls everthing from the NewsCategories entity
//END CONFIG AREA ---------------------------------------------------------- 
$sql = "select * FROM NewsCategories";

get_header(); #defaults to header_inc.php

$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));


echo '<div align="center"><h4>SQL STATEMENT: <font color="red">' . $sql . '</font></h4></div>';
if (mysqli_num_rows($result) > 0){#there are records - present data
    echo'<table class="table table-striped table-hover ">
         <thead>
           <tr>
             <th>Title</th>
             <th>Description</th>
           </tr>
         </thead>
         <tbody>';
    
	while ($row = mysqli_fetch_assoc($result)){# pull data from associative array
	  
            echo '<tr>
                  <td><a href="news_view.php?id=' . $row['CategoryID'] . '">' . $row['Category'] . '</a></td>
                  <td>' . $row['Description'] . '</td>
                  </tr>';
	}
    
    echo '  </tbody>
            </table> ';
    
}else {#no records
	echo '<div align="center">Sorry, there are no records that match this query</div>';
}
@mysqli_free_result($result);
get_footer(); #defaults to footer_inc.php
?>

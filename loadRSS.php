<?php
 //loadRSS.php
/* 
**
**This file will load all the latest stories from the list of rss feeeds (listed below), 
**
**
*/
//simple.php is the all-in-one php file for simplepie, a tool that parses rss feeds
    require 'include\simple.php';

    class RSSLoader
    {
        public $feed;
       
        public function __construct()
        {
        
        }

        public function reloadRSS() //loads rss feed into database
        {
            $feed = new SimplePie(); 
            //sets the list of rss feeds, add as many as you want
            $urlList = array(
                //need to add the website of the rss feed
                'http://www.website.xxxxxxxxx'
            
            );
            $feed->set_feed_url($urlList);
            //enables caching
            $feed->enable_cache(false);
            //sets cache location
            $feed->set_cache_location('cache');
            $feed->strip_htmltags(true);
            $feed->strip_htmltags(array('blink', 'font', 'marquee','strong','embed','figure','p', 'a','em', 'inset', ));

            //sets how long the cache is good for in seconds
            $feed->set_cache_duration(600);
            //initializes feed
            $feed->init();
            //this method call is to ensure thhat the feed handles the various types of content data properly
            $feed->handle_content_type();
            // default starting item
            $start = 0;

            // default number of items to display. 0 = all
            $length = 50; 
        


            $iConn = @mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die(myerror(__FILE__,__LINE__,mysqli_connect_error())) ;
    
            // if single item, set start to item number and length to 1
            if(isset($_GET['item']))
            {
                $start = $_GET['item'];
                $length = 1;
               
            }
 


            // loop through items
            foreach($feed->get_items($start,$length) as $item)
            {
                
                $ArticleImage = '';
                $ArticleTitle = mysqli_real_escape_string($iConn, $item->get_title());
                $ArticleDescription = mysqli_real_escape_string($iConn, $item->get_description());
                $ArticleURL = mysqli_real_escape_string($iConn, $item->get_permalink());
                $ArticleDate = $item->get_date( $date_format = 'Y-m-d H:i:s');
                
                
               /* $result = preg_match_all('/<img.*?src\s*=.*?>/', $ArticleDescription , $matches, PREG_SET_ORDER);  */
                $result = preg_match_all('/<img.*?src\s*=.*?>/', $ArticleDescription , $images);
                if($result)
                { 
                    $ArticleImage =$images[0][0];
           
                
                }
           
                $ArticleDescription=str_replace($ArticleImage, '', $ArticleDescription);     
                $ArticleImage=str_replace('<img src=\"', '', $ArticleImage);
                $ArticleImage=str_replace('\">', '', $ArticleImage);
                $now = date("Y-m-d H:i:s");
             
                $sql3 = 'INSERT INTO wn17_p3_articles(Title, Description, URL, Time_Stamp, OriginalDate, ImageURL)  VALUES("' . $ArticleTitle . '","'  .  $ArticleDescription . '","'  .  $ArticleURL .'","' . $now .'","' . $ArticleDate.'","' . $ArticleImage . '");' ;
                
                mysqli_query($iConn, $sql3);
    
          
    
            }
    
        }
    }
?>

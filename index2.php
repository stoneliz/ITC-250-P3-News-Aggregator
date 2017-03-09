<?php
//index2.php
//found online, might help to think it through

include 'credentials.php';


//SQL statement selects Cateogries that exist in both tables
$sql = "SELECT DISTINCT Category FROM wn17_newsCategory nc INNER JOIN wn17_newsFeed nf ON nc.CategoryID = nf.CategoryID";


$connect = @mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);

$result = mysqli_query($connect,$sql) or die(trigger_error(mysqli_error($connect), E_USER_ERROR));


//an array of objects, an object is 1 of 9 of the feeds, each object has all the information from the database portaining to it. They are added to 1 of 3 arrays for each cateogry

echo '<h1 align="center">News Aggregator</h1>';

while ($row = mysqli_fetch_assoc($result)) {
    $category = $row['Category'];
    echo "<br/><b>$category</b><br/>";
    $sql2 = "SELECT FeedName, FeedID FROM wn16_newsFeed nf INNER JOIN wn16_newsCategory nc ON nc.CategoryID = nf.CategoryID WHERE Category = '$category'";
    $result2 = mysqli_query($connect,$sql2) or die(trigger_error(mysqli_error($connect), E_USER_ERROR));    
    while ($row2 = mysqli_fetch_assoc($result2)) {
        $feedlink = new Feed($row2['FeedName'], $row2['FeedID']);
        $feedlink->FeedLinks();      
    }   
}
@mysqli_free_result($result);
@mysqli_free_result($result2);
@mysqli_close($connect);


class Feed {
    public $FeedName = '';
    public $FeedID = 0;
    
    function __construct($FeedName, $FeedID) {
        
        $this->FeedName = $FeedName;
        $this->FeedID = $FeedID;

    }//end constructor
    
    public function FeedLinks() {
    echo '<a href="feedview.php?feedid='.$this->FeedID.'">'.$this->FeedName.'</a><br/>';
    }
    
}//end class

?>
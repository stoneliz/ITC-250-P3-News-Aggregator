<?php 
Class NewsItem
{
    public $Title='';
    public $Description='';
    public $DateAdded='';
    public $URL='';
    public $ImageURL='';
    
    public function __construct($Title, $Description,  $URL, $date, $image)
    {

        $this->Title = $Title;
        $this->Description = $Description;
        $this->DateAdded = $date;
        $this->URL = $URL;
        $this->ImageURL = $image;

    }

    public function display() //creates each individual news story on page
    {

        echo '
    

        <tr>
            <td><a href="'.$this->URL .'"><h3>'.$this->Title.'</a></h3></td>
            <td valign="bottom"><small>Date Published '.$this->DateAdded.'</small></td>
        </tr>
       <tr>
       
       </tr><td><img src="'.
             $this->ImageURL.'
        "heigh="200" width="200"</td><tr>
            <td><p>'.$this->Description.'</p></td>
    
        
       
        </tr>';
        


    }

}

//List.php

<? php
    
//SQL call that shows all books in the Category table (a list page):
 SELECT * FROM NewsCategories


<? php

    
$sql = 
"
select CONCAT(c.Category) NewsCategory, f.NewsID, f.NewsTitle, f.Description " . PREFIX . "Category c where f.CategoryID=c.CategoryID order by c.Category desc";


View.php

//SQL call for the view page to display all Music Subcategories:

SELECT * FROM  NewsFeed WHERE CategoryID=1


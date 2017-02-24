<?php
/**

 * @package nmAdmin
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.014 2012/06/09
 * @link http://www.newmanix.com/
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see config_inc.php 
 * @todo none
 */
 
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials



// I havnt figure out how to do the admin login yet, but I think probably this would be the place 


$redirect_to_login = TRUE; #if true, will redirect to admin login page, else redirect to main site index

# END CONFIG AREA ---------------------------------------------------------- 

if($redirect_to_login)
{# redirect to current login page
	myRedirect($config->adminLogin);
}else{#redirect to main site index
	myRedirect(VIRTUAL_PATH . "index.php"); 
}
?>

<?php
error_reporting(E_ALL);

// include the database configuration
require_once('dbconfig.php');

define("USER_HOME_DIR", "/home/stud/s3254869");
require(USER_HOME_DIR . "/php/Smarty-2.6.26/Smarty.class.php");
$smarty = new Smarty();
$smarty->template_dir = USER_HOME_DIR . "/php/Smarty-Work-Dir/templates";
$smarty->compile_dir = USER_HOME_DIR . "/php/Smarty-Work-Dir/templates_c";
$smarty->cache_dir = USER_HOME_DIR . "/php/Smarty-Work-Dir/cache";
$smarty->config_dir = USER_HOME_DIR . "/php/Smarty-Work-Dir/configs";

if (!($connection = @ mysql_connect(DB_HOST . ":" . DB_PORT, DB_USER, DB_PW)))
{
	display_error();
}

// if database can not be selected, then show error
if (!mysql_select_db('winestore', $connection))
{
	display_error();
}


$region = clean_sql($_GET, "region", 4, $connection);
$startyear = clean_sql($_GET, "startyear", 4, $connection);
$mincost = clean_sql($_GET, "mincost", 50, $connection);
$maxcost = clean_sql($_GET, "maxcost", 50, $connection);
$wine = clean_sql($_GET, "wine", 50, $connection);
$winery = clean_sql($_GET, "winery", 100, $connection);
$endyear = clean_sql($_GET, "endyear", 4, $connection);
$stocknum = clean_sql($_GET, "stocknum", 5, $connection);

$errordisplay ='';


if($startyear>$endyear)
{
	$errordisplay.="Start year must be same as or before the end year";
	$errordisplay.="<br/>";
}



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


if($stocknum!='')
{
	if(!is_numeric($stocknum))
	{
		$errordisplay.="The value entered for minimum stock is not valid";
		$errordisplay.="<br/>";
	}
}

if($mincost!='')
{
	if(!is_numeric($mincost))
	{
		$errordisplay.="The value entered for the minimum cost is not valid";
		$errordisplay.="<br/>";
	}
}

if($maxcost!='')
{
	if(!is_numeric($maxcost))
	{
		$errordisplay.="The value for maximum cost entered is not valid";
		$errordisplay.="<br/>";
	}
}

?>

<!DOCTYPE HTML PUBLIC
		"-//W3C//DTD HTML 4.01 Transitional//EN"
		 "http://www.w3.org/TR/html401/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Wine Search (s3254869)</title>
</head>
<body style="background-color:#FFFFCC">
<h2 align="center">Search Results</h2>

<?php
if($errordisplay!='')
{
	$str1= "<font 
color='red'><strong>".$errordisplay."</strong></font>";
	$str1.="<br/><br/>";
	$str1.="There are errors in what you have submitted. Please 
enter search criteria again.";
	$str1.="<br/>";
	$str1.="<a href='javascript:history.back()'>Go back to fix 
search criteria.</a><br/>";
	$smarty->assign('errorresult', $str1);
}

?>

<?php


$pdo_variable = new PDO($dsn, DB_USER, DB_PW);
$pdo_variable->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$sql = "SELECT w.wine_name, gv.variety, w.year, wi.winery_name, r.region_name, inv.cost
		 FROM wine w, winery wi, region r, inventory inv, grape_variety gv, wine_variety wv
		 WHERE w.winery_id = wi.winery_id
		 AND wi.region_id = r.region_id
		 AND w.wine_id = wv.wine_id
		 AND w.wine_id = inv.wine_id
		 AND gv.variety_id = wv.variety_id";


<?php
session_start();

$host="localhost"; 
$username="userLCourse"; 
$password="lcpasswd"; 
$db_name="dbListeCoursesOrig"; 
$connexion=mysql_connect($host, $username, $password)or die("Cannot Connect to Mysql Server"); 
mysql_select_db($db_name)or die("cannot select Database");
mysql_set_charset("UTF8");
?>

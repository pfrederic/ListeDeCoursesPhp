<?php
$host="localhost"; 
$username="userLCourse"; 
$password="lcpasswd"; 
$db_name="dbListeCoursesOrig"; 
$connexion=mysql_connect($host, $username, $password)or die("Cannot Connect to Mysql Server"); 
mysql_select_db($db_name)or die("cannot select Database");

//ToDo récupérer le numéro de la liste correspondant à la famille du membre logué (requête sql)
//$_SESSION['noListeEnCours']=0;
$noListeEnCours=0;
//ToDo
mysql_set_charset("UTF8");
?>

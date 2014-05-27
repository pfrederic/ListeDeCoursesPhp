<?php
session_start();

$host="localhost"; 
$username="userLCourse"; 
$password="lcpasswd"; 
$db_name="dbListeCoursesOrig"; 
$connexion=mysql_connect($host, $username, $password)or die("Cannot Connect to Mysql Server"); 
mysql_select_db($db_name)or die("cannot select Database");
mysql_set_charset("UTF8");

/******************************************************/
/*                      FUNCTION                      */
/******************************************************/

/**
 * Fonction qui retourne l'identifiant de la famille du membre connecté.
 * Elle exécute une requête de projection, et retourne le résultat de cette requête.
 */
function connaitreFamilleDuMembreConnecte()
{
	$sql="select familleId from membre where membreId=".$_SESSION['membre'];
	//echo $sql;
	$res=mysql_query($sql);
	$ligne=mysql_fetch_array($res);
	return $ligne['familleId'];
}

/**
 * Fonction qui retourne l'identifiant de la liste en cours, du membre connecté.
 * Elle exécute une requête de projection, et retourne le résultat de cette requête.
 */
function connaitreListeDuMembreConnecte()
{
	$idFamille=connaitreFamilleDuMembreConnecte();
	$sql="select listeId from liste where familleId=".$idFamille." and enCours=TRUE";
	//echo $sql;
	$res=mysql_query($sql);
	$ligne=mysql_fetch_array($res);
	return $ligne['listeId'];
}

?>

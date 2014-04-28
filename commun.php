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
 * Fonction qui renseigne l'identifiant de la liste en cours, du membre connecté.
 * Elle exécute une requête de projection, et renseigne une variable session du résultat de cette requête.
 * @param int $idFamille Identifiant de la famille du membre
 */
function connaitreListeDuMembreConnecte($idFamille)
{
	$sql="select listeId from liste where familleId=".$idFamille;
	//echo $sql;
	$res=mysql_query($sql);
	$ligne=mysql_fetch_array($res);
	$_SESSION['liste']=$ligne['listeId'];
}

?>

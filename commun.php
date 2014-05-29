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
	//echo $sql."</br>";
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
	//echo $sql."</br>";
	$res=mysql_query($sql);
	$ligne=mysql_fetch_array($res);
	return $ligne['listeId'];
}

/**
 * Fonction qui créer une nouvelle liste de course pour la famille, si elle n'existe pas
 * Elle exécute une requête de création de dans la table liste. Une liste qui est n'est pas active, elle s'apparente à la liste suivante. Ou crée une liste active pour une nouvelel famille par exemple.
 * @param  boolean $statut Défini si la liste créée est une liste activte (enCours=TRUE) ou non (enCours=FALSE)
 */
 function creerNouvelleListe($statut)
 {
	$famille=connaitreFamilleDuMembreConnecte();
	$sql="select ifnull(max(listeId),0)+1 nouvelIdentifiant from liste";
	//echo $sql;
	$res=mysql_query($sql);
    $ligne=mysql_fetch_assoc($res);
    $idNouvelleListe=$ligne['nouvelIdentifiant'];
	$sql="insert into liste(listeId, familleId, enCours) values(".$idNouvelleListe.", ".$famille.", ".$statut.")";
	//echo $sql;
    $res=mysql_query($sql);
    return $idNouvelleListe;
 }

?>

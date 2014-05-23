<?php
include("commun.php");

/******************************************************/
/*                      FUNCTION                      */
/******************************************************/

/**
 * Fonction qui recherche l'identifiant de la famille selon le code saisi
 * Elle exécute une requête de projection pour connaître l'identifiant de la famille selon le code saisi. Si il existe bien une famille, alors fait appel à fonction pour mettre à jour le membre. Sinon renvoi un tableau pour informer de l'inexistance de la famille.
 */
function rechercheFamille()
{
	$code=$_GET['code'];
	$sql="select familleId from famille where familleCode=".$code;
	//echo $sql;
	$res=mysql_query($sql);
	$json=array();
	if(mysql_num_rows($res))
	{
		$ligne=mysql_fetch_array($res);
		$idFamille=$ligne['familleId'];
		affectationFamille($idFamille);
		connaitreListeDuMembreConnecte($idFamille);
		$json['famille'][]=array("success"=>"it works!");
	}
	else{
		$json=array();
		$json['famille'][]=array("erreur"=>"no family");		
	}
	echo json_encode($json);
}

/**
 * Fonction qui renseigne un identifiant d'une famille pour un membre
 * Exécute une requête pour mettre à jour le membre avec l'identifiant de la famille passé en paramètre
 * @param int $idFamille Identifiant de la famille à affecté au membre
 */
function affectationFamille($idFamille)
{
	$idMembre=$_SESSION['membre'];
	$sql="update membre set familleId=".$idFamille." where membreId='".$idMembre."'";
	//echo $sql;
	$res=mysql_query($sql);
}

/**
 * Fonction qui créer un nouvel identifiant, pour la futur famille.
 * Elle exécute une requête qui trouve le dernier identifiant, et lui ajoute 1. Si il y a pas de dernier identifiant, renvoi 0.
 */
function creerNouvelIdentifiant()
{
	$sql="select ifnull(max(membreId),0)+1 nouvelId from famille";
	//echo $sql;
	$res=mysql_query($sql);
	$ligne=mysql_fetch_array($res);
	return $ligne['nouvelId'];
}

/**
 * Fonction qui génére un code famille aléatoire et unique.
 * Elle génére un code tant que celui-ci est existant dans la base (exécution d'une requête pour vérifier si le code généré n'est pas déjà existant). Une fois qu'un code généré est unique, on renvoi ce code.
 */
function genererCodeFamille()
{
	$codeFamille;
	$ligne="lorem ipsum";
	while(!empty($ligne))
	{
		$codeFamille=rand(0, 99999999999);
		$sql="select familleCode from famille where familleCode=".$codeFamille;
		//echo $sql;
		$res=mysql_query($sql);
		$ligne=mysql_fetch_array($res);
	}
	return $codeFamille;
}

/**
 * Fonction qui créé une famille dans la base.
 * Fait appel à la fonction "genererCodeFamille()" et "creerNouvelIdentifiant()", puis exécute une requête d'insertion.
 */
function creationFamille()
{
	$json=array();
	$libelle=$_GET['libelle'];
	$membre=$_SESSION['membre'];
	$codeFamille=genererCodeFamille();
	$identifiant=creerNouvelIdentifiant();
	$sql="insert into famille(familleId, familleLib, familleCode, responsableId) values(".$identifiant.", '".$libelle."', ".$codeFamille.", '".$membre."')";
	//echo $sql;
	$res=mysql_query($sql);
	$sql="update membre set familleId=".$identifiant." where membreId='".$membre."'";
	$res=mysql_query($sql);
	$json['famille'][]=array("success"=>"");
	echo json_encode($json);
}

function getCodeFamille()
{
	$json=array();
	$membre=$_SESSION['membre'];
	$sql="select familleCode from famille where responsableId=".$membre;
	$result=mysql_query($sql);
	if(mysql_num_rows($result))
	{
		$ligne=mysql_fetch_assoc($result);
		$json['famille'][]=$ligne;
	}
	else
	{
		$json['famille'][]=array("erreur"=>"not allowed");
	}
	echo json_encode($json);
}

/******************************************************/
/*                    ACTION & Json                   */
/******************************************************/
if(isset($_GET['action']))
{
	$action=$_GET['action'];
	switch($action)
	{
		case "rejoindre":
			rechercheFamille();
			break;
		case "creation";
			creationFamille();
			break;
		case "code":
			getCodeFamille();
			break;
	}
}
?>
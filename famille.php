<?php
include("commun.php");

/******************************************************/
/*                      FUNCTION                      */
/******************************************************/

/**
 * Fonction qui recherche l'identifiant de la famille selon le code saisi
 * Elle exécute une première requête de projection pour connaître l'identifiant de la famille selon le code saisi. Si il existe bien une famille, alors une seconde requête est exécuté pour mettre jour le membre avec l'identifiant de la famille. Sinon renvoi un tableau pour informer de l'inexistance de la famille.
 */
function rechercheEtAffectationFamille()
{
	$code=$_GET['code'];
	$idMembre=$_SESSION['membre'];
	$sql="select familleId from famille where familleCode=".$code;
	//echo $sql;
	$res=mysql_query($sql);
	$json=array();
	if(mysql_num_rows($res))
	{
		$ligne=mysql_fetch_array($res);
		$idFamille=$ligne['familleId'];
		$sql="update membre set familleId=".$idFamille." where membreId='".$idMembre."'";
		//echo $sql;
		$res=mysql_query($sql);		
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
 * Fonction qui créer un nouvel identifiant, pour la futur famille.
 * Elle exécute une requête pour connaitre le dernier identifiant de la table famille, ajoute 1 à cette valeur, et renvoi cette dernière.
 */
function creerNouvelIdentifiant()
{
	$sql="select max(familleId) lastId from famille";
	//echo $sql;
	$res=mysql_query($sql);
	$ligne=mysql_fetch_array($res);
	$nouvelleIdentifiant=$ligne['lastId']+1;
	return $nouvelleIdentifiant;
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
	$libelle=$_GET['libelle'];
	$membre=$_SESSION['membre'];
	$codeFamille=genererCodeFamille();
	$identifiant=creerNouvelIdentifiant();
	$sql="insert into famille(familleId, familleLib, familleCode, responsableId) values(".$identifiant.", '".$libelle."', ".$codeFamille.", '".$membre."')";
	//echo $sql;
	$res=mysql_query($sql);
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
		rechercheEtAffectationFamille();
		break;
		case "creation";
		creationFamille();
		break;
	}
}
?>
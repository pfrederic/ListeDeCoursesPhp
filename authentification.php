<?php
include("commun.php");

/******************************************************/
/*                      FUNCTION                      */
/******************************************************/

/**
 * Fonction qui génère un nouvel identifiant pour un noveau membre
 * Elle exécute une requête qui trouve le dernier identifiant, et lui ajoute 1. Si il y a pas de dernier identifiant, renvoi 0.
 * @return int Le dernier identifiant de la base, plus 1
 */
function nouveauIdMembre()
{
	$sql='select ifnull(max(membreId),0)+1 nouvelId from membre';
	//echo $sql;
	$res=mysql_query($sql);
	$ligne=mysql_fetch_array($res);
	return $ligne['nouvelId'];
}

/**
 * Fonction qui renseigne l'identifiant du membre et celui de sa famille
 * Elle exécute une requête de projection, qui vérifie si ce qui a été saisi correspond aux données de la base.
 * Si c'est le cas, les données renvoyées par la requête sont renseigné dans des sessions.
 * Sinon, renvoi erreur.
 * @param int $idMembre Identifiant saisi par l'utilisateur
 * @param int $mdpMembre Mont de passe saisi par l'utilisateur
 */
function connexionMembre($loginMembre, $mdpMembre)
{
	$monTableau=array();
	$sql="select membreId, membreMdp, familleId from membre where membreLogin='".$loginMembre."' and membreMdp='".$mdpMembre."'";
	//echo $sql;
	$res=mysql_query($sql);
	$monTableau=array();
	if(mysql_num_rows($res))
	{
		$ligne=mysql_fetch_assoc($res);
		$monTableau['authentification'][]=$ligne;
		$_SESSION['membre']=$ligne['membreId'];
	}
	else
	{
		$monTableau['authentification'][]=array("erreur"=>"erreur");
	}
	
	echo json_encode($monTableau);
}


/**
 * Fontction qui enregistre un nouvel utilisateur (membre).
 * Exécute une requête sql d'insertion, avec les infrmations saisies par l'utilisateur sur l'application. Si l'identifiant saisi est déjà enregistrer dans la base, retour d'une erreur en Json.
 * @param  string $loginMembre
 * @param  string $mdpMembre
 * @param  string $mailMembre
 * @param  date   $naissanceMembre
 * @return string Chaine au format Json qui renvoi un message de succès ou d'échec.
 */
function enregistrerNouveauMembre($loginMembre, $mdpMembre, $mailMembre, $naissanceMembre){
	$json=array();
	$idMembre=nouveauIdMembre();
	$sql="insert into membre(membreId, membreLogin, membreMdp, membreMail, membreDateNaissance) values(".$idMembre.", '".$loginMembre."', '".$mdpMembre."', '".$mailMembre."', '".$naissanceMembre."')";
	//echo $sql;
	$res=mysql_query($sql);
	if(strpos(mysql_error(), "mailUnique"))
	{		
		$json['register'][]=array("erreur"=>"mail");;
	}
	else if(strpos(mysql_error(), "loginUnique"))
	{
		$json['register'][]=array("erreur"=>"login");;
	}
	else
	{
		$json['register'][]=array("success"=>"");;
		$_SESSION['membre']=$idMembre;
	}
	echo json_encode($json);
}

/******************************************************/
/*                    ACTION & Json                   */
/******************************************************/
if(isset($_GET['action']))
{
	$action=$_GET['action'];
	$login=$_GET['login'];
	$mdp=$_GET['mdp'];
	
	//Traitement des chaînes reçu pour éviter les injections SQL
	$login=addcslashes($login, '\'\"');
	$mdp=addcslashes($mdp, '\'\"');
	
	switch($action)
	{
		case "connection":
			connexionMembre($login, $mdp);
			break;
		case "register":
			$mail=$_GET['mail'];
			$naissance=$_GET['naissance'];
			enregistrerNouveauMembre($login, $mdp, $mail, $naissance);
		break;
	}	
} 
?>
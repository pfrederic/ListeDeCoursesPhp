<?php
include("commun.php");

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

/**
 * Fonction qui renseigne l'identifiant du membre et celui de sa famille
 * Elle exécute une requête de projection, qui vérifie si ce qui a été saisi correspond aux données de la base.
 * Si c'est le cas, les données renvoyées par la requête sont renseigné dans des sessions.
 * Sinon, renvoi erreur.
 * @param int $idMembre Identifiant saisi par l'utilisateur
 * @param int $mdpMembre Mont de passe saisi par l'utilisateur
 */
function renseignerSession($idMembre, $mdpMembre)
{
	$monTableau=array();
	$req="SELECT membreId, membreMdp, familleId FROM membre WHERE membreId='".$idMembre."' AND membreMdp='".$mdpMembre."'";
	$res=mysql_query($req);
	$monTableau=array();
	if(mysql_num_rows($res))
	{
		$ligne=mysql_fetch_assoc($res);
		$monTableau['authentification'][]=$ligne;
		$_SESSION['famille']=$ligne['familleId'];
		$_SESSION['membre']=$ligne['membreId'];
		connaitreListeDuMembreConnecte($ligne['familleId']);
	}
	else
	{
		$monTableau['authentification'][]=array("erreur"=>"erreur");
	}
	connaitreListeDuMembreConnecte($_SESSION['famille']);
	
	echo json_encode($monTableau);
}

function enregistrerNouveauMembre($idMembre, $mdpMembre, $mailMembre, $naissanceMembre){
	$req="insert into membre(membreId, membreMdp, membreMail, membreDateNaissance) values('".$idMembre."', '".$mdpMembre."', '".$mailMembre."', '".$naissanceMembre."')";
	//echo $req;
	$res=mysql_query($req);
}

/******************************************************/
/*                    ACTION & Json                   */
/******************************************************/
if(isset($_GET['action']))
{
	$action=$_GET['action'];
	$id=$_GET['id'];
	$mdp=$_GET['mdp'];
	
	//Traitement des chaînes reçu pour éviter les injections SQL
	$id=addcslashes($id, '\'\"');
	$mdp=addcslashes($mdp, '\'\"');
	
	switch($action)
	{
		case "login":
		renseignerSession($id, $mdp);
		break;
		case "register":
		$mail=$_GET['mail'];
		$naissance=$_GET['naissance'];
		enregistrerNouveauMembre($id, $mdp, $mail, $naissance);
		break;
	}	
} 
?>
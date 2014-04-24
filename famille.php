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
	if(mysql_num_rows($res))
	{
		$ligne=mysql_fetch_array($res);
		$idFamille=$ligne['familleId'];
		$sql="update membre set familleId=".$idFamille." where membreId=".$idMembre;
		//echo $sql;
		$res=mysql_query($sql);
	}
	else{
		$json=array();
		$json['famille'][]=array("erreur"=>"no family");;
		echo json_encode($json);
	}
}

/******************************************************/
/*                    ACTION & Json                   */
/******************************************************/
if(isset($_GET['action']))
{
	$action=$_GET['action'];
	if($action=="rejoindre")
	{		
		rechercheEtAffectationFamille();
	}
}
?>
<?php
include("commun.php");
if(isset($_GET['action']) && $_GET['action']=="login")
{
	$id=$_GET['id'];
	$mdp=$_GET['mdp'];
	
	$id=addcslashes($id, '\'\"');
	$mdp=addcslashes($mdp, '\'\"');
	
	$monTableau=array();
	$req="SELECT membreId, membreMdp FROM membre WHERE membreId='".$id."' AND membreMdp='".$mdp."'";
	$res=mysql_query($req);
	$monTableau=array();
	if(mysql_num_rows($res))
	{
		$ligne=mysql_fetch_assoc($res);
		$monTableau['authentification'][]=$ligne;
	}
	else
	{
		$monTableau['authentification'][]=array("erreur"=>"erreur");
	}
	
	echo json_encode($monTableau);
} 
?>
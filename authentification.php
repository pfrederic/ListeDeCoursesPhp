<?php
include("commun.php");

function connaitreListeDuMembreConnecte($idFamille)
{
	$sql="select listeId from liste where familleId=".$idFamille;
	//echo $sql;
	$res=mysql_query($sql);
	$ligne=mysql_fetch_array($res);
	$_SESSION['liste']=$ligne['listeId'];
}
if(isset($_GET['action']) && $_GET['action']=="login")
{
	$id=$_GET['id'];
	$mdp=$_GET['mdp'];
	
	$id=addcslashes($id, '\'\"');
	$mdp=addcslashes($mdp, '\'\"');
	
	$monTableau=array();
	$req="SELECT membreId, membreMdp, familleId FROM membre WHERE membreId='".$id."' AND membreMdp='".$mdp."'";
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
	
	echo json_encode($monTableau);
} 
?>
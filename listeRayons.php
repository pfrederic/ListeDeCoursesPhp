<?php
include("commun.php");
//requete sql
if(isset($_GET['action'])&& $_GET['action']=='ajout')
{
	$nomDuRayon=$_GET['nomRayon'];
	$req='select ifnull(max(rayonId),0)+1 from rayon';
	$res1=mysql_query($req);
	$result=mysql_fetch_row($res1);
	$prochainNumeroRayon=$result[0];

	$sql="insert into rayon(rayonId,rayonLib)values($prochainNumeroRayon,'".$nomDuRayon."')";
	$res=mysql_query($sql);
}
if(isset($_GET['action'])&& $_GET['action']=='delete')
{
	$tabNoRayon=$_GET['tabNoRayon'];
	foreach($tabNoRayon as $noRayon)
	{
		$sql="delete from  rayon where rayonId=$noRayon";
		$res=mysql_query($sql);
	}
}

$sql = "select * from rayon"; 
//execution
$result = mysql_query($sql);
//le tableau
$monTableau = array();
if(mysql_num_rows($result))//s'il y a un resultat
{
	while($ligne=mysql_fetch_assoc($result))
	{
		$monTableau['rayonInfos'][]=$ligne;
	}
}
echo json_encode($monTableau); 
?> 

<?php
include("commun.php");
if(isset($_GET['action']))
{
	$action=$_GET['action'];
	$noProduit=$_GET['produitId'];
	$qte=$_GET['qte'];

	if($action=="ajout")
	{
		$liste=$_SESSION['liste'];
		//ToDo Faire appel au bon numéro de liste
		$sql = "insert into contenuListe(listeId,produitId,listeQte) values(".$liste.", ".$noProduit.", ".$qte.")"; 
		//echo $sql;
		$result = mysql_query($sql);
	}
}
$json = array();
$sql="select contenuListe.produitId as produitId ,produitLib,listeQte from contenuListe inner join produit on produit.produitId=contenuListe.produitId where listeId=".$_SESSION['liste'];
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res))
{
	$json['listeDeCourse'][]=$row;
}
echo json_encode($json);
?> 

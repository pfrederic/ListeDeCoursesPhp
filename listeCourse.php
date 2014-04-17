<?php
include("commun.php");
if(isset($_GET['action']))
{
	$action=$_GET['action'];
	$noProduit=$_GET['produitId'];
	$qte=$_GET['qte'];

	if($action=="ajout")
	{
		//ToDo Faire appel au bon numéro de liste
		$sql = "insert into contenuListe(listeId,produitId,listeQte) values(0,".$noProduit.", ".$qte.")"; 
		//echo $sql;
		$result = mysql_query($sql);
	}
}
$json = array();
$sql2="select contenuListe.produitId as produitId ,produitLib,listeQte from contenuListe inner join produit on produit.produitId=contenuListe.produitId";
$result2=mysql_query($sql2);
while($row=mysql_fetch_assoc($result2))
{
	$json['listeDeCourse'][]=$row;
}
echo json_encode($json);
?> 

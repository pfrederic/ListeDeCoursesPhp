<?php
include("commun.php");


/******************************************************/
/*                      FUNCTION                      */
/******************************************************/
/**
 * Fonction qui ajoute un produit dans la liste
 * Elle exécute une requête d'insertion dans la table contenuListe.
 */
function ajoutProduitDansLaListe()
{
	$noProduit=$_GET['produitId'];
	$qte=$_GET['qte'];
	$liste=connaitreListeDuMembreConnecte();
	$sql="insert into contenuListe(listeId,produitId,listeQte) values(".$liste.", ".$noProduit.", ".$qte.")"; 
	//echo $sql;
	$result=mysql_query($sql);
}

/**
 * Fonction qui modifie la quantité d'un produit de la liste
 * Elle exécute une rquête de mise à jour, selon la valeur saisie par l'utilisateur.
 */
function modificationQuantite()
{
	$noProduit=$_GET['produitId'];
	$qte=$_GET['qte'];
	$liste=connaitreListeDuMembreConnecte();
	$sql="update contenuListe set listeQte=".$qte." where produitId=".$noProduit." and listeId=".$liste;
	//echo $sql;
	$result=mysql_query($sql);
}

/**
 * Fonction qui supprime un produit de la liste
 * Elle exécute une requête de suppression, selon le produit choisi par l'utilisateur.
 */
function suppressionProduit() 
{
	$noProduit=$_GET['produitId'];
	$liste=connaitreListeDuMembreConnecte();
	$sql="delete from contenuListe where produitId=".$noProduit." and listeId=".$liste;
	//echo $sql;
	$result=mysql_query($sql);
}

/******************************************************/
/*                    ACTION & Json                   */
/******************************************************/
if(isset($_GET['action']))
{
	$action=$_GET['action'];
	switch($action)
	{
		case "ajout":
			ajoutProduitDansLaListe();
			break;
		case "modification":
			modificationQuantite();
			break;
		case "suppression":
			suppressionProduit();
			break;
	}
}

$json = array();
$liste=connaitreListeDuMembreConnecte();
$sql="select contenuListe.produitId as produitId ,produitLib,listeQte from contenuListe inner join produit on produit.produitId=contenuListe.produitId where listeId=".$liste;
$res=mysql_query($sql);
if(mysql_num_rows($res))//s'il y a un resultat
{
	while($row=mysql_fetch_assoc($res))
	{
		$json['listeDeCourse'][]=$row;
	}
}
else
{
	$json['listeDeCourse'][]=array("" => "");
}
echo json_encode($json);
?> 

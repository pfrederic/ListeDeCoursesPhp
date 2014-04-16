<?php
include("commun.php");

/******************************************************/
/*                      FUNCTION                      */
/******************************************************/
/**
 * Fonction qui retire tous les produit non-désiré de la liste.
 * Elle exécute une requête de supression de données autant de fois qu'il à de produit à retirer.
 */
function annulerProduitDeLaListe($noListe)
{
	$tabNoArticle=$_GET['tabNoArticle'];
	//Boucle qui parcours le tableau de produit à retirer de la liste
	foreach($tabNoArticle as $noArticle)
	{
		$sql="delete from contenuListe where produitId=".$noArticle." and listeId=".$noListe;
		//echo $sql;
		$res=mysql_query($sql);
	}
}

/**
 * Fonction qui change le statut du produit, pour le passer à "poser dans le caddy".
 * Ell exécute une requête de modification de données pour passer à true le "dansCaddy" de la table "contenuListe".
 */
 function poserProduitDansCaddy($noListe)
 {
	$tabNoArticle=$_GET['tabNoArticle'];
	//Boucle qui parcours le tableau de produit à retirer de la liste
	foreach($tabNoArticle as $noArticle)
	{
		$sql="update contenuListe set dansCaddy=true where produitId=".$noArticle." and listeId=".$noListe;
		//echo $sql;
		$res=mysql_query($sql);
	}
 }
/******************************************************/
/*                    ACTION & Json                   */
/******************************************************/

if(isset($_GET['action']))
{
	$action=$_GET['action'];
	switch($action)
	{
		case "acheter":
			poserProduitDansCaddy($noListeEnCours);
			break;
		case "annuler":
			annulerProduitDeLaListe($noListeEnCours);
			break;
		case "reporter": 
			break;
	}
}

//requete sql
$sql = "select produit.produitId as produitId, produitLib, listeQte, rayon.rayonId as rayonId, rayonLib from produit inner join rayon on rayon.rayonId=produit.rayonId inner join contenuListe on contenuListe.produitId=produit.produitId inner join liste on liste.listeId=contenuListe.listeId where enCours=true and liste.listeId=".$noListeEnCours; 

//execution
$result = mysql_query($sql);
//le tableau
$monTableau = array();
if(mysql_num_rows($result))//s'il y a un resultat
{
	while($ligne=mysql_fetch_assoc($result))
	{
		$monTableau['coursesAFaire'][]=$ligne;
	}
}

echo json_encode($monTableau); 
?> 

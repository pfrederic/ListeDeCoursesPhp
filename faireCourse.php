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

 function creerNouvelleListe()
 {
	//ToDO l'appel à l'id de la famille
	$sql="select max(listeId) derniereListeDeLaFamille from liste where familleId=1";
        $res=mysql_query($sql);
        $ligne=mysql_fetch_array($res);
        $idNouvelleListe=$ligne['derniereListeDeLaFamille'];
        //ToDO l'appel à l'id de la famille
        //$sql="insert into liste(listeId, familleId, enCours) values(".$idNouvelleListe.", "..", FALSE)";
        $res=mysql_query($sql);
        return $idNouvelleListe;
 }
 
/**
 * Fonction qui report sur la liste de course suivante
 * Elle exécute une requête pour connaitre la liste de course suivante, la crée aux besoins, et ajoute les produits dans la nouvelle liste
 */
 function reporterProduitListeSuivante($noListe)
 {
	$idListe;
        //Recherche de la liste suivante
	//ToDO l'appel à l'id de la famille
        //$sql="select max(listeId) listeDeLaFamilleInactif from liste where familleId=".." and enCours='FALSE'";
        //echo $sql;
        $res=mysql_query($sql);
        $ligne=mysql_fetch_array($res);
        if($res==FALSE)
        {
        	$idListe=creerNouvelleListe();
        }
        else
        {
        	$idListe=$ligne['listeDeLaFamilleInactif'];
        }
        $tabNoArticle=$_GET['tabNoArticle'];
        //Boucle qui parcours le tableau de produit à reporter de la liste
        foreach($tabNoArticle as $noArticle)
        {
		//ToDO l'appel à l'id de la famille
                //$sql="update contenuListe set listeId=".$idListe." where listeId=".$noListe." and familleId=".;
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
			reporterProduitListeSuivante($noListeEnCours);
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

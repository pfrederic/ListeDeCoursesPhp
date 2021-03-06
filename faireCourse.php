<?php
include("commun.php");

/******************************************************/
/*                      FUNCTION                      */
/******************************************************/

/**
 * Fonction qui archive une liste si tous les produits de la liste sont pris.
 * Elle vérifie que les produits sont tous pris. Si c'est le cas alors elle passe la liste en cours à false. Puis elle cherche la liste suivante et l'active.
 * Si elle ne trouve pas de liste, alors elle fait appel à une fonction pour en créer une.
 */
function archivageListe()
{
	$liste=connaitreListeDuMembreConnecte();
	$famille=connaitreFamilleDuMembreConnecte();

	$requeteTousLesProduitsDeLaListe="select count(*) nbProduitDansLaListe from contenuListe where listeId=".$liste;
	$requeteTousLesProduitsPritDeLaListe="select count(*) nbProduitPritDansLaListe from contenuListe where listeId=".$liste." and membreId is not null";

	$resTousLesProduits=mysql_query($requeteTousLesProduitsDeLaListe);
	$resTousLesProduitsPrit=mysql_query($requeteTousLesProduitsPritDeLaListe);

	$ligneTousLesProduits=mysql_fetch_array($resTousLesProduits);
	$ligneTousLesProduitsPrit=mysql_fetch_array($resTousLesProduitsPrit);

	$nbTousLesProduits=$ligneTousLesProduits['nbProduitDansLaListe'];
	$nbTousLesProduitsPrit=$ligneTousLesProduitsPrit['nbProduitPritDansLaListe'];

	if($nbTousLesProduits==$nbTousLesProduitsPrit)
	{
		$reqArchivageListe="update liste set enCours=0 where listeId=".$liste;
		$res=mysql_query($reqArchivageListe);
		//Recherche de la liste suivante
	    $sql="select max(listeId) listeDeLaFamilleInactif from liste where familleId=".$famille." and enCours='FALSE' and listeId>".$liste;
	    //echo $sql;
	    $res=mysql_query($sql);
	    $ligne=mysql_fetch_array($res);
	    if($ligne['listeDeLaFamilleInactif']==NULL)
	    {
			//echo "If ";
	      	$newListe=creerNouvelleListe("TRUE");
	    }
	    else
	    {
	    	//echo "Else";
	    	$newListe=$ligne['listeDeLaFamilleInactif'];
	    	$sql="update liste set enCours=1 where listeId=".$newListe;
	    	//echo $sql;
	    	$res=mysql_query($sql);
	    }
	}

}

/**
 * Fonction qui retire tous les produit non-désiré de la liste.
 * Elle exécute une requête de supression de données autant de fois qu'il à de produit à retirer.
 */
function annulerProduitDeLaListe()
{
	$tabNoArticle=$_GET['tabNoArticle'];
	$liste=connaitreListeDuMembreConnecte();
	//Boucle qui parcours le tableau de produit à retirer de la liste
	foreach($tabNoArticle as $noArticle)
	{
		$sql="delete from contenuListe where produitId=".$noArticle." and listeId=".$liste;
		//echo $sql;
		$res=mysql_query($sql);
	}
}

/**
 * Fonction qui change le statut du produit, pour le passer à "poser dans le caddy".
 * Elle exécute une requête de modification de données pour passer à true le "dansCaddy" de la table "contenuListe".
 */
 function poserProduitDansCaddy()
 {
	$tabNoArticle=$_GET['tabNoArticle'];
	$liste=connaitreListeDuMembreConnecte();
	//Boucle qui parcours le tableau de produit à retirer de la liste
	foreach($tabNoArticle as $noArticle)
	{
		$sql="update contenuListe set membreId='".$_SESSION['membre']."' where produitId=".$noArticle." and listeId=".$liste;
		//echo $sql;
		$res=mysql_query($sql);
	}
 }

/**
 * Fonction qui report sur la liste de course suivante
 * Elle exécute une requête pour connaitre la liste de course suivante, la crée aux besoins, et ajoute les produits dans la nouvelle liste
 */
 function reporterProduitListeSuivante()
 {
 	$newListe=NULL;
	$oldListe=connaitreListeDuMembreConnecte();
	$famille=connaitreFamilleDuMembreConnecte();
    //Recherche de la liste suivante
    $sql="select max(listeId) listeDeLaFamilleInactif from liste where familleId=".$famille." and enCours='FALSE' and listeId>".$oldListe;
    //echo $sql;
    $res=mysql_query($sql);
    $ligne=mysql_fetch_array($res);
    if($ligne['listeDeLaFamilleInactif']==NULL)
    {
		//echo "If ";
      	$newListe=creerNouvelleListe("FALSE");
    }
    else
    {
		//echo "Else ";
        $newListe=$ligne['listeDeLaFamilleInactif'];
    }
    $tabNoArticle=$_GET['tabNoArticle'];
    //Boucle qui parcours le tableau de produit à reporter de la liste
    foreach($tabNoArticle as $noArticle)
    {
        $sql="update contenuListe set listeId=".$newListe." where listeId=".$oldListe." and produitId=".$noArticle;
        //echo $sql;
		$res=mysql_query($sql);
    }
  }

/**
 * Fonction qui trie tous les produits de la liste par ordre des rayons du magasin choisi
 * Elle exécute une requête pour connaitre les produit (AVEC le nom du rayon dans lequel il se trouve) de la liste qui se trouve dans la magasin, par ordre de rayon, du dit magasin, les ajoutes dans un tableau,
 * et fait appel à la fonction listeProduitsPasDansMagasin, en passant en paramètre le tableau dans lequel se trouve les produits par ordre des rayons.
 */
function listeProduitsOrdreRayonsMagasin()
{
	$liste=connaitreListeDuMembreConnecte();
	if(isset($_GET['magasin']))
	{
		$_SESSION['magasin']=$_GET['magasin'];
		$magasin=$_GET['magasin'];
	}
	else
	{
		$magasin=$_SESSION['magasin'];
	}
	//requete sql
	$sql = "select produit.produitId as produitId, produitLib, listeQte, rayon.rayonId as rayonId, rayonLib 
	from produit inner join rayon on rayon.rayonId=produit.rayonId 
	inner join contenuListe on contenuListe.produitId=produit.produitId 
	inner join liste on liste.listeId=contenuListe.listeId 
	inner join organisation on rayon.rayonId=organisation.rayonId
	where enCours=true 
	and liste.listeId=".$liste." 
	and magasinId=".$magasin." 
	and membreId is null 
	order by organisationOrdre asc";
	//echo $sql;
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
	listeProduitsPasDansMagasin($monTableau);
}

/**
 * Fonction qui cherche tous les produits de la liste qui ne se trouve pas le magasin choisi
 * Elle exécute une requête pour connaitre les produits (SANS le nom du rayon dans lequel il se trouve) de la liste qui sont introuvable dans le magasin choisi par l'utilisateur, et l'ajoute au tableau passé en paramètre.
 * Et affiche du tableau au format JSON.
 * @param  array $Json Tableau contenant déjà les produits par odre des rayons du magasin.
 */
function listeProduitsPasDansMagasin($Json)
{
	$liste=connaitreListeDuMembreConnecte();
	if(isset($_GET['magasin']))
	{
		$magasin=$_GET['magasin'];
	}
	else
	{
		$magasin=$_SESSION['magasin'];
	}
	//requete sql
	$sql = "select produit.produitId as produitId, produitLib, listeQte 
	from produit inner join rayon on rayon.rayonId=produit.rayonId 
	inner join contenuListe on contenuListe.produitId=produit.produitId 
	inner join liste on liste.listeId=contenuListe.listeId 
	where enCours=true 
	and liste.listeId=".$liste." 
	and membreId is null 
	and rayon.rayonId not in (
		select rayonId
		from organisation
		where magasinId=".$magasin.")";
	//echo $sql;
	//execution
	$result = mysql_query($sql);
	if(mysql_num_rows($result))//s'il y a un resultat
	{
		while($ligne=mysql_fetch_assoc($result))
		{
			$Json['coursesAFaire'][]=$ligne;
		}
}

echo json_encode($Json); 
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
			poserProduitDansCaddy();
			archivageListe();
			break;
		case "annuler":
			annulerProduitDeLaListe();
			archivageListe();
			break;
		case "reporter": 
			reporterProduitListeSuivante();
			archivageListe();
			break;
	}
	listeProduitsOrdreRayonsMagasin();
}
?> 

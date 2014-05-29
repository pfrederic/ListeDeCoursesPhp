-- Scritp : Création de procédures pour la base de données
-- Auteur : F.Pignoly
-- Date création : 28/05/2014
-- Date modification : 28/05/2014

-- Procédure qui génère un nouvel identifiant pour un nouvelle liste
DELIMITER |
DROP procedure if exists creationNouvelleListe|
CREATE procedure creationNouvelleListe (IN pIdFamille int, IN pStatutListe tinyint)
BEGIN
	DECLARE varNouvelIdListe integer;
	SELECT ifnull(max(listeId),0)+1 INTO varNouvelIdListe
	FROM liste;

	INSERT INTO liste(listeId, familleId, enCours) VALUES(varNouvelIdListe, pIdFamille, pStatutListe);

	SELECT varNouvelIdListe AS nouvelIdentifiant;
END|
DELIMITER ;
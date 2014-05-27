-- Scritp : Création de trigger pour la base de données
-- Auteur : F.Pignoly
-- Date création : 27/05/2014
-- Date modification : 27/05/2014

-- Trigger qui archive la liste une fois que tous les produits ont été produits
DELIMITER //
DROP TRIGGER IF EXISTS dbListeCoursesOrig.archivageListe//
CREATE TRIGGER dbListeCoursesOrig.archivageListe
AFTER UPDATE ON contenuListe
FOR EACH ROW
BEGIN
	-- Déclaration variable
	DECLARE varNombreProduitDansLaListe integer;
	DECLARE varNombreProduitPritDansLaListe integer;
	DECLARE varFamilleId integer;
	DECLARE varFamilleAListe integer;

	-- Recherche du nombre de produit dans la liste
	SELECT COUNT(*) INTO varNombreProduitDansLaListe
	FROM contenuListe
	WHERE listeId=NEW.listeId
	GROUP BY (listeId);
	-- Recherche du nombre de produit prit dans la liste
	SELECT COUNT(*) INTO varNombreProduitPritDansLaListe
	FROM contenuListe
	WHERE listeId=NEW.listeId
	AND membreId IS NOT NULL
	GROUP BY (listeId);

	IF(varNombreProduitPritDansLaListe = varNombreProduitDansLaListe) THEN
		SELECT familleId INTO varFamilleId
		FROM liste
		WHERE listeId=NEW.listeId;

		SELECT MAX(listeId) INTO varFamilleAListe
		FROM liste
		WHERE familleId=varFamilleId
		AND enCours=FALSE
		AND listeId>NEW.listeId;

		-- Archivage de la liste
		UPDATE liste SET enCours=FALSE WHERE listeId=NEW.listeId;

		IF(varFamilleAListe >= 0) THEN
			UPDATE liste SET enCours=TRUE WHERE listeID=varFamilleAListe;
		ELSE
			SELECT ifnull(max(listeId),0)+1 INTO varFamilleAListe
			FROM liste;
			INSERT INTO liste(listeId, familleId, enCours) VALUES (varFamilleAListe, varFamilleId, TRUE);
		END IF;
	END IF;
END//

DROP TRIGGER IF EXISTS dbListeCoursesOrig.creationListePourNouvelleFamille//
CREATE TRIGGER dbListeCoursesOrig.creationListePourNouvelleFamille
AFTER INSERT ON famille
FOR EACH ROW
BEGIN
	DECLARE varNouvelIdListe integer;
	SELECT ifnull(max(listeId),0)+1 INTO varNouvelIdListe
	FROM liste;

	INSERT INTO liste(listeId, familleId, enCours) VALUES(varNouvelIdListe, NEW.familleId, FALSE);
END//

DELIMITER ;
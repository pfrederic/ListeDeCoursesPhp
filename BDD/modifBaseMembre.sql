-- Script : Modification table membre
-- Auteur : F.Pignoly
-- Date création : 09/05/2014
-- Date modification : 09/05/2014

-- Changement nom du champs membreId en membreLogin
ALTER TABLE membre
CHANGE membreLogin membreId;

-- Suppression de la clé étrangère de la table famille (qui es lié à la table membre)
ALTER TABLE famille
DROP FOREIGN KEY famille_ibfk_1;

-- Suppression de clé primaire de la table membre
ALTER TABLE membre
DROP PRIMARY KEY;

-- Suppression de toutes les occurences de la table
DELETE FROM membre;

-- Création d'un nouveau champs identifiant pour la table membre, qui sera clé primaire
ALTER TABLE membre
ADD membreId int PRIMARY KEY;

-- ajout d'une occurence dans la table
INSERT INTO membre(membreId, membreMdp, membreMail, membreDateNaissance, familleId) VALUES (1, "fred", "ini", "pignoly.frederic@gmail.com", "1994-02-02", 1);

-- Modification format champs responsableId table famille de varchar en int
ALTER TABLE famille
MODIFY responsableId int;

-- Création de la contrainte d'intégrité entre les tables famille et membre
ALTER TABLE famille
ADD FOREIGN KEY (responsableId) REFERENCES membre(membreId);

-- Création d'un index unique sur le champs membreLogin
CREATE unique INDEX loginUnique ON membre(membreLogin);

-- Modifcation format du champs membreId de la table contenuListe
ALTER TABLE contenuListe MODIFY membreId int;
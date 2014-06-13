-- Script : Création de la base dans sa version final
-- Auteur : F.Pignoly
-- Date création : 12/06/2014
-- Date modification : 12/06/2014

-- Création de la base
CREATE DATABASE dbListeCoursesOrig;

USE dbListeCoursesOrig;

-- Création des utilisateurs
GRANT ALL PRIVILEGES ON dbListeCoursesOrig.* TO userLCourse@localhost IDENTIFIED BY 'lcpasswd';
GRANT ALL PRIVILEGES ON dbListeCoursesOrig.* TO technicien@'%' IDENTIFIED BY 'ini01';

-- Création table
-- magasin
DROP TABLE IF EXISTS magasin;
CREATE TABLE magasin (
magasinId 		int(11) NOT NULL,
magasinLib 		varchar(30) DEFAULT NULL,
PRIMARY KEY (magasinId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- rayon
DROP TABLE IF EXISTS rayon;
CREATE TABLE rayon (
rayonId 		int(11) NOT NULL,
rayonLib 		varchar(30) DEFAULT NULL,
PRIMARY KEY (rayonId),
UNIQUE KEY rayonUnique (rayonLib)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- organisation
DROP TABLE IF EXISTS organisation;
CREATE TABLE organisation (
magasinId 		int(11) NOT NULL DEFAULT '0',
rayonId 		int(11) NOT NULL DEFAULT '0',
organisationOrdre 	int(11) NOT NULL DEFAULT '0',
PRIMARY KEY (magasinId, rayonId, organisationOrdre),
CONSTRAINT FOREIGN KEY (magasinId) REFERENCES magasin (magasinId),
CONSTRAINT FOREIGN KEY (rayonId) REFERENCES rayon (rayonId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- famille
DROP TABLE IF EXISTS famille;
CREATE TABLE famille (
familleId 		int(11) NOT NULL,
familleLib 		varchar(25) NOT NULL,
familleCode 		int(11) NOT NULL,
responsableId 		int(11) DEFAULT NULL,
PRIMARY KEY (familleId),
UNIQUE KEY responsableUnique (responsableId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- liste
DROP TABLE IF EXISTS liste;
CREATE TABLE liste (
listeId 		int(11) NOT NULL DEFAULT '0',
familleId 		int(11) NOT NULL,
enCours 		tinyint(1) DEFAULT '1',
CONSTRAINT FOREIGN KEY (familleId) REFERENCES famille (familleId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- produit
DROP TABLE IF EXISTS produit;
CREATE TABLE produit (
produitId 		int(11) NOT NULL,
produitLib 		varchar(50) DEFAULT NULL,
rayonId 		int(11) DEFAULT NULL,
PRIMARY KEY (produitId),
CONSTRAINT FOREIGN KEY (rayonId) REFERENCES rayon (rayonId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ContenuListe
DROP TABLE IF EXISTS contenuListe;
CREATE TABLE contenuListe (
listeId 		int(11) NOT NULL DEFAULT '0',
produitId 		int(11) NOT NULL DEFAULT '0',
listeQte 		int(11) DEFAULT NULL,
membreId 		int(11) DEFAULT NULL,
PRIMARY KEY (listeId,produitId),
CONSTRAINT FOREIGN KEY (listeId) REFERENCES liste (listeId),
CONSTRAINT FOREIGN KEY (produitId) REFERENCES produit (produitId)
 ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- membre
DROP TABLE IF EXISTS membre;
CREATE TABLE membre (
membreId int(11) NOT NULL DEFAULT '0',
membreLogin varchar(50) NOT NULL DEFAULT '',
membreMdp varchar(50) DEFAULT NULL,
membreMail varchar(50) DEFAULT NULL,
membreDateNaissance date DEFAULT NULL,
familleId int(11) DEFAULT NULL,
PRIMARY KEY (membreId),
UNIQUE KEY loginUnique (membreLogin),
UNIQUE KEY mailUnique (membreMail)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Ajout des contraintes d'intégrités
ALTER TABLE membre
ADD FOREIGN KEY (familleId) REFERENCES famille(familleId);

ALTER TABLE famille
ADD FOREIGN KEY (responsableId) REFERENCES membre(membreId);

-- utilisateur

DROP TABLE IF EXISTS utilisateur;
CREATE TABLE utilisateur (
userId 			int(11) NOT NULL,
userLogin 		varchar(50) DEFAULT NULL,
userMdp 		varchar(250) DEFAULT NULL,
userNom 		varchar(50) DEFAULT NULL,
userPrenom 		varchar(50) DEFAULT NULL,
userDateNaissance 	date DEFAULT NULL,
PRIMARY KEY (userId),
UNIQUE KEY userUnique (userLogin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Jeu d'essai
INSERT INTO famille(familleId, familleLib, familleCode, responsableId) VALUES (1, 'Dupont', 2586, NULL);
INSERT INTO liste(listeId, familleId, enCours) VALUES (0, 1, 0), (1, 1, 1);
INSERT INTO magasin(magasinId, magasinLib) VALUES (1, 'Leclerc'), (2, 'Auchan'), (3, 'Carrefour'), (4, 'Géant Casino');
INSERT INTO rayon(rayonId, rayonLib) VALUES (7, 'Jardinerie'), (3, 'Légume'), (1, 'viande'), (2, 'yaourt');
INSERT INTO organisation(magasinId, rayonId, organisationOrdre) VALUES (1, 1, 2), (2, 1, 2),(4, 1, 2), (1, 2, 3), (2, 2, 3), (4, 2, 3), (1, 3, 4), (4, 3, 1);
INSERT INTO produit(produitId, produitLib, rayonId) VALUES (1, 'pilon de poulet', 1), (2, 'cote de boeuf', 1), (3, 'flanby', 2), (4, 'yaourt nature', 2), (5, 'feuille de chenes blonde', 3), (6, 'carote', 3), (7, 'Riz au lait', 2);
INSERT INTO contenuListe(listeId, produitId, listeQte, membreId) VALUES (0, 3, 6, 1), (0, 1, 5, 1), (1, 6, 4, 1), (1, 3, 5, NULL), (0, 4, 2, 1);
INSERT INTO membre(membreId, membreLogin, membreMdp, membreMail, membreDateNaissance, familleId) VALUES (1, 'fred', 'ini', 'pignoly.frederic@gmail.com', '1994-02-02', 1), (2, 'fredo', 'ff', 'ff@fff.com', '1994-02-02', 1);
INSERT INTO utilisateur(userId, userLogin, userMdp, userNom, userPrenom, userDateNaissance) VALUES (1, 'technicien', '*5725D947895497F3E64028AC181890D7454BE757', 'Pignoly', 'Frédéric', '1994-02-02');

UPDATE famille SET responsableId=1 WHERE familleId=1;

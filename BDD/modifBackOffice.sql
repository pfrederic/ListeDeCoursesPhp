-- Script : Modification pour la connexion de l'utilisateur sur le back office
-- Auteur : F.Pignoly
-- Date création : 29/05/2014
-- Date modification : 29/05/2014

-- Création d'un utilisateur MySql spécifique à l'application
GRANT ALL PRIVILEGES ON dbListeCoursesOrig.* TO technicien@'%' IDENTIFIED BY 'ini01';

-- Ajout d'une table
CREATE TABLE utilisateur (
userId				int primary key,
userLogin			varchar(50),
userMdp				varchar(250),
userNom				varchar(50),
userPrenom			varchar(50),
userDateNaissance	date
) engine=innodb;

-- Ajout d'un index
CREATE UNIQUE INDEX userUnique ON utilisateur(userLogin);

-- Ajout d'une occurence
INSERT INTO utilisateur(userId, userLogin, userMdp, userNom, userPrenom, userDateNaissance) VALUES(1, "technicien", PASSWORD("ini01"), "Pignoly", "Frédéric", "1994-02-02");
-- script : Modification de la base de données : Ajout de la table membre, modification de la table famille, et modification table contenuListe
-- auteur : F.Pignoly
-- date création : 18/04/2014
-- date modification : 18/04/2014

-- ajout de la table membre
DROP TABLE if exists membre;
CREATE TABLE membre (
membreId		varchar(50) primary key,
membreMdp		varchar(50),
membreMail		varchar(50),
membreDateNaissance 	date,
familleId	int NULL
 ) engine=innodb; 

-- modification de la table famille
ALTER TABLE famille
ADD responsableId varchar(50);

-- modification table contenuListe
ALTER TABLE contenuListe
CHANGE dansCaddy membreId varchar(50);

-- ajout de contraintes fonctionnelles
ALTER TABLE membre
ADD foreign key (familleId) references famille(familleId);

ALTER TABLE famille
ADD foreign key (responsableId) references membre(membreId);

ALTER TABLE contenuListe
ADD foreign key (membreId) references membre(membreId);

-- ajout d'une occurence dans la table
INSERT INTO membre(membreId, membreMdp, membreMail, membreDateNaissance, familleId) VALUES ("fred", "ini", "pignoly.frederic@gmail.com", "1994-02-02", 1);

-- Script : Script de modification de la base (création d'index).
-- Auteur : F.Pignoly
-- Date création : 27/04/2014
-- Date modification : 27/04/2014

-- Création d'index sur le champs "membreMail" de la table "membre"
CREATE UNIQUE INDEX mailUnique ON membre(membreMail);

-- Création d'index sur le champs "familleCode" de la table "famille"
CREATE UNIQUE INDEX codeUnique ON famille(familleCode);

-- Création d'index sur le champs "responsableId" de la table "famille"
CREATE UNIQUE INDEX responsableUnique ON famille(responsableId);

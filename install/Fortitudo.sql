CREATE TABLE {{DB_PREFIX}}T_Personne
(
    id_personne SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL,
    adresse VARCHAR(150) NOT NULL,
    code_postal CHAR(5) NOT NULL,
    ville VARCHAR(30) NOT NULL,
    telephone CHAR(10) NOT NULL,
    mail VARCHAR(70) NOT NULL UNIQUE CHECK (mail LIKE '%@%'),
    
    PRIMARY KEY (id_personne)
) ENGINE=INNODB;

CREATE TABLE {{DB_PREFIX}}T_Societe
(
    id_personne SMALLINT UNSIGNED NOT NULL UNIQUE,
    raison_sociale VARCHAR(50) NOT NULL,
    
    PRIMARY KEY (id_personne),
    
    CONSTRAINT fk_id_societe
        FOREIGN KEY (id_personne) 
        REFERENCES {{DB_PREFIX}}T_Personne(id_personne)
        ON UPDATE CASCADE
        ON DELETE NO ACTION
) ENGINE=INNODB;

CREATE TABLE {{DB_PREFIX}}T_Personne_Physique
(
    id_personne SMALLINT UNSIGNED NOT NULL UNIQUE,
    nom VARCHAR (30) NOT NULL,
    prenom VARCHAR (30) NOT NULL,
    
    PRIMARY KEY (id_personne),
    
    CONSTRAINT fk_id_personne_physique
        FOREIGN KEY (id_personne) 
        REFERENCES {{DB_PREFIX}}T_Personne(id_personne)
        ON UPDATE CASCADE
        ON DELETE NO ACTION
) ENGINE=INNODB;

CREATE TABLE {{DB_PREFIX}}T_MOA
(
    id_personne SMALLINT UNSIGNED NOT NULL UNIQUE,
    
    PRIMARY KEY (id_personne),
    
    CONSTRAINT fk_id_MOA
        FOREIGN KEY (id_personne) 
        REFERENCES {{DB_PREFIX}}T_Personne_Physique(id_personne)
        ON UPDATE CASCADE
        ON DELETE NO ACTION
) ENGINE=INNODB;

CREATE TABLE {{DB_PREFIX}}T_Salarie
(
    id_personne SMALLINT UNSIGNED NOT NULL UNIQUE,
    
    PRIMARY KEY (id_personne),
    
    CONSTRAINT fk_id_salarie
        FOREIGN KEY (id_personne) 
        REFERENCES {{DB_PREFIX}}T_Personne_Physique(id_personne)
        ON UPDATE CASCADE
        ON DELETE NO ACTION
) ENGINE=INNODB;

CREATE TABLE {{DB_PREFIX}}T_Membre
(
    id_personne SMALLINT UNSIGNED NOT NULL UNIQUE,
    actif BOOLEAN DEFAULT 1 NOT NULL,
    
    PRIMARY KEY (id_personne),
    
    CONSTRAINT fk_id_membre
        FOREIGN KEY (id_personne)
        REFERENCES {{DB_PREFIX}}T_Personne_Physique(id_personne)
        ON UPDATE CASCADE
        ON DELETE NO ACTION
) ENGINE=INNODB;

CREATE TABLE {{DB_PREFIX}}T_Projet
(
    num_projet SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL,
    titre_projet VARCHAR(50) NOT NULL,
    date_creation DATE NOT NULL,
    id_MOA SMALLINT UNSIGNED NOT NULL,
    id_societe SMALLINT UNSIGNED NOT NULL,
    
    PRIMARY KEY (num_projet),
    
    CONSTRAINT fk_id_MOA_projet
        FOREIGN KEY (id_MOA) 
        REFERENCES {{DB_PREFIX}}T_MOA(id_personne)
        ON UPDATE CASCADE
        ON DELETE NO ACTION,
    
    CONSTRAINT fk_num_societe_projet
        FOREIGN KEY (id_societe) 
        REFERENCES {{DB_PREFIX}}T_Societe(id_personne)
        ON UPDATE CASCADE
        ON DELETE NO ACTION
) ENGINE=INNODB;

CREATE TABLE {{DB_PREFIX}}T_Poste
(
    num_poste SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL,
    libelle VARCHAR(50) NOT NULL,
    tarif_horaire DECIMAL(7,2) NOT NULL CHECK (tarif_horaire > 0.0),
    part_salariale DECIMAL(7,2) NOT NULL CHECK (part_salariale > 0.0),
    
    PRIMARY KEY (num_poste)
) ENGINE=INNODB;

CREATE TABLE {{DB_PREFIX}}T_Devis
(
    num_devis SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL,
    date_emission DATE NOT NULL,
    est_accepte BOOLEAN DEFAULT 0 NOT NULL,
    date_acceptation DATE NULL,
    num_projet SMALLINT UNSIGNED NOT NULL,
    
    PRIMARY KEY(num_devis),
    
    CONSTRAINT fk_num_projet
        FOREIGN KEY (num_projet) 
        REFERENCES {{DB_PREFIX}}T_Projet(num_projet)
        ON UPDATE CASCADE
        ON DELETE NO ACTION
) ENGINE=INNODB;

CREATE TABLE {{DB_PREFIX}}T_Service
(
    num_service SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL,
    libelle VARCHAR(50) NOT NULL,
    tarif_mensuel DECIMAL(7,2) NOT NULL CHECK (tarif_mensuel > 0.0),
    
    PRIMARY KEY(num_service)
) ENGINE=INNODB;

CREATE TABLE {{DB_PREFIX}}T_Identifiant
(
    num_identifiant SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL,
    mot_de_passe VARCHAR(123) NOT NULL, -- Maximum de la fonction crypt()
    cle_recuperation VARCHAR(23) NULL UNIQUE, -- Taille de uniqid()
    id_membre SMALLINT UNSIGNED NOT NULL UNIQUE,
    
    PRIMARY KEY(num_identifiant),
    
    CONSTRAINT fk_id_membre_identifiant
        FOREIGN KEY (id_membre) 
        REFERENCES {{DB_PREFIX}}T_Membre(id_personne)
        ON UPDATE CASCADE
        ON DELETE NO ACTION
) ENGINE=INNODB;

CREATE TABLE {{DB_PREFIX}}TJ_Devis_Salarie_Poste
(
    num_poste SMALLINT UNSIGNED NOT NULL,
    num_devis SMALLINT UNSIGNED NOT NULL,
    id_personne SMALLINT UNSIGNED NOT NULL,
    nbr_heures TINYINT UNSIGNED NOT NULL CHECK (nbr_heures > 0),
    
    PRIMARY KEY(num_poste, num_devis, id_personne),
    
    CONSTRAINT fk_num_poste_devis
        FOREIGN KEY (num_poste) 
        REFERENCES {{DB_PREFIX}}T_Poste(num_poste)
        ON UPDATE CASCADE
        ON DELETE NO ACTION,
        
    CONSTRAINT fk_num_devis_devis
        FOREIGN KEY (num_devis) 
        REFERENCES {{DB_PREFIX}}T_Devis(num_devis)
        ON UPDATE CASCADE
        ON DELETE NO ACTION  ,
        
    CONSTRAINT fk_id_personne_devis
        FOREIGN KEY (id_personne) 
        REFERENCES {{DB_PREFIX}}T_Salarie(id_personne)
        ON UPDATE CASCADE
        ON DELETE NO ACTION    
) ENGINE=INNODB;

CREATE TABLE {{DB_PREFIX}}TJ_Devis_Service
(
    num_devis SMALLINT UNSIGNED NOT NULL,
    num_service SMALLINT UNSIGNED NOT NULL,
    date_debut DATE NOT NULL,
    date_fin  DATE NULL,
    
    PRIMARY KEY(num_devis, num_service),
        
    CONSTRAINT fk_num_devis_service
        FOREIGN KEY (num_devis) 
        REFERENCES {{DB_PREFIX}}T_Devis(num_devis)
        ON UPDATE CASCADE
        ON DELETE NO ACTION,
        
    CONSTRAINT fk_num_service_service
        FOREIGN KEY (num_service) 
        REFERENCES {{DB_PREFIX}}T_Service(num_service)
        ON UPDATE CASCADE
        ON DELETE NO ACTION
) ENGINE=INNODB;

CREATE TABLE {{DB_PREFIX}}T_Paiement
(
    id_paiement SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL,
    num_devis SMALLINT UNSIGNED NOT NULL,
    id_societe SMALLINT UNSIGNED NOT NULL,
    date_paiement DATE NOT NULL,
    quantite_payee DECIMAL(7,2) NOT NULL CHECK (quantite_payee > 0.0),
    
    PRIMARY KEY(id_paiement),
        
    CONSTRAINT fk_num_devis_societe
        FOREIGN KEY (num_devis) 
        REFERENCES {{DB_PREFIX}}T_Devis(num_devis)
        ON UPDATE CASCADE
        ON DELETE NO ACTION,

    CONSTRAINT fk_num_societe_societe
        FOREIGN KEY (id_societe) 
        REFERENCES {{DB_PREFIX}}T_Societe(id_personne)
        ON UPDATE CASCADE
        ON DELETE NO ACTION
) ENGINE=INNODB;

CREATE TABLE {{DB_PREFIX}}TJ_Societe_MOA
(
    id_societe SMALLINT UNSIGNED NOT NULL,
    id_MOA SMALLINT UNSIGNED NOT NULL,
    titre VARCHAR(30) NOT NULL,
    
    PRIMARY KEY(id_societe, id_MOA),
    
    CONSTRAINT fk_num_societe_MOA
        FOREIGN KEY (id_societe) 
        REFERENCES {{DB_PREFIX}}T_Societe(id_personne)
        ON UPDATE CASCADE
        ON DELETE NO ACTION,
    
    CONSTRAINT fk_num_MOA_MOA
        FOREIGN KEY (id_MOA) 
        REFERENCES {{DB_PREFIX}}T_MOA(id_personne)
        ON UPDATE CASCADE
        ON DELETE NO ACTION
) ENGINE=INNODB;

CREATE TABLE {{DB_PREFIX}}TJ_Membre_Projet
(
    id_membre SMALLINT UNSIGNED NOT NULL,
    num_projet SMALLINT UNSIGNED NOT NULL,
    
    PRIMARY KEY(id_membre, num_projet),
    
    CONSTRAINT fk_id_membre_projet
        FOREIGN KEY (id_membre) 
        REFERENCES {{DB_PREFIX}}T_Membre(id_personne)
        ON UPDATE CASCADE
        ON DELETE NO ACTION,
    
    CONSTRAINT fk_num_projet_projet
        FOREIGN KEY (num_projet) 
        REFERENCES {{DB_PREFIX}}T_Projet(num_projet)
        ON UPDATE CASCADE
        ON DELETE NO ACTION
) ENGINE=INNODB;

-- Création de vues

-- Personne physique
CREATE VIEW {{DB_PREFIX}}V_Personne_Physique
AS SELECT 
    P.id_personne, PP.nom, PP.prenom, P.adresse, P.code_postal, P.ville, P.telephone, P.mail
FROM
    {{DB_PREFIX}}T_Personne_Physique AS PP
    INNER JOIN {{DB_PREFIX}}T_Personne AS P
    ON P.id_personne = PP.id_personne;

-- MOA
CREATE VIEW {{DB_PREFIX}}V_MOA
AS SELECT 
    PP.id_personne, PP.nom, PP.prenom, PP.adresse, PP.code_postal, PP.ville, PP.telephone, PP.mail
FROM
    {{DB_PREFIX}}T_MOA AS MOA
    INNER JOIN {{DB_PREFIX}}V_Personne_Physique AS PP
    ON MOA.id_personne = PP.id_personne;

-- Salarié
CREATE VIEW {{DB_PREFIX}}V_Salarie
AS SELECT 
    PP.id_personne, PP.nom, PP.prenom, PP.adresse, PP.code_postal, PP.ville, PP.telephone, PP.mail
FROM
    {{DB_PREFIX}}T_Salarie AS S
    INNER JOIN {{DB_PREFIX}}V_Personne_Physique AS PP
    ON S.id_personne = PP.id_personne;

-- Membre
CREATE VIEW {{DB_PREFIX}}V_Membre
AS SELECT 
    PP.id_personne, PP.nom, PP.prenom, PP.adresse, PP.code_postal, PP.ville, PP.telephone, PP.mail, M.actif
FROM
    {{DB_PREFIX}}T_Membre AS M
    INNER JOIN {{DB_PREFIX}}V_Personne_Physique AS PP
    ON M.id_personne = PP.id_personne;

-- Identifiant
CREATE VIEW {{DB_PREFIX}}V_Identifiant
AS SELECT 
    M.id_personne, M.mail, M.actif, I.mot_de_passe, I.cle_recuperation
FROM
    {{DB_PREFIX}}T_Identifiant AS I
    INNER JOIN {{DB_PREFIX}}V_Membre AS M
    ON I.id_membre = M.id_personne;

-- Société
CREATE VIEW {{DB_PREFIX}}V_Societe
AS SELECT 
    P.id_personne, S.raison_sociale, P.adresse, P.code_postal, P.ville, P.telephone, P.mail
FROM
    {{DB_PREFIX}}T_Societe AS S
    INNER JOIN {{DB_PREFIX}}T_Personne AS P
    ON P.id_personne = S.id_personne;
START TRANSACTION;

CREATE ROLE beheerder;
CREATE ROLE medewerker;

CREATE USER Yetkin IDENTIFIED BY 'wachtwoord';
CREATE USER Ronald IDENTIFIED BY 'wachtwoord';
CREATE USER Thijs IDENTIFIED BY 'wachtwoord';
CREATE USER Iain IDENTIFIED BY 'wachtwoord';
CREATE USER Piet IDENTIFIED BY 'wachtwoord';
CREATE USER Henk IDENTIFIED BY 'wachtwoord';

GRANT beheerder TO Yetkin, Ronald, Thijs, Iain;
GRANT medewerker TO Piet, Henk;

GRANT INSERT, UPDATE, SELECT, DELETE ON Yerothia.* TO medewerker;
GRANT ALL PRIVILEGES ON Yerothia.* TO beheerder;

COMMIT;

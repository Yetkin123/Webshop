USE `Yerothia`;

-- Emails
-- UPDATE
UPDATE Emails
SET email = 'info@degroenevinger.nl'
WHERE emailId = 2;

-- Accounts
-- UPDATE
UPDATE Accounts
SET wachtwoord = 'nieuw_wachtwoord'
WHERE accountId = 2;

-- DELETE
DELETE FROM Accounts
WHERE accountId = 1;

-- Contacten
-- UPDATE
UPDATE Contacten
SET telefoonnummer = 0623486254
WHERE contactId = 2;

-- DELETE
DELETE FROM Contacten
WHERE contactId = 1;

-- Klanten
-- UPDATE
UPDATE Klanten
SET kvkNummer = 56362576
WHERE klantId = 2;

-- DELETE
DELETE FROM Klanten
WHERE klantId = 1;

-- Adressen
-- UPDATE
UPDATE Adressen
SET postcode = '1554HG', huisnummer = 10
WHERE adresId = 2;

-- Abonnementen
-- UPDATE
UPDATE Abonnementen
SET naam = 'Nieuwe naam', omschrijving = 'Nieuwe omschrijving'
WHERE abonnementId = 2;

-- DELETE
DELETE FROM Abonnementen
WHERE abonnementId = 1;

-- Producten
-- UPDATE
UPDATE Producten
SET naam = 'Nieuwe productnaam', omschrijving = 'Nieuwe productomschrijving'
WHERE productId = 2;

-- AbonnementsProducten
-- UPDATE
-- Voor AbonnementsProducten hebben we alleen DELETE's omdat het een koppelingsentiteit is.

-- DELETE
DELETE FROM AbonnementsProducten
WHERE productId = 1 AND abonnementId = 1;

-- Betaalgegevens
-- UPDATE
UPDATE Betaalgegevens
SET rekeningnummer = 'NL91ABNA0417164300'
WHERE betaalId = 2;

-- DELETE
DELETE FROM Betaalgegevens
WHERE betaalId = 1;

-- AutomatischeIncassos
-- UPDATE
UPDATE AutomatischeIncassos
SET status = 'Betaald'
WHERE abonnementId = 2 AND betaalId = 2;

-- DELETE
DELETE FROM AutomatischeIncassos
WHERE abonnementId = 1 AND betaalId = 1;

-- Orders
-- UPDATE
UPDATE Orders
SET status = 'Geleverd'
WHERE orderId = 2;

-- Facturen
-- UPDATE
UPDATE Facturen
SET status = 'Open'
WHERE factuurId = 2;

-- DELETE
DELETE FROM Facturen
WHERE factuurId = 1;

-- Orderregels
-- UPDATE
UPDATE Orderregels
SET aantal = 2
WHERE orderregelId = 2;

-- Retouren
-- UPDATE
UPDATE Retouren
SET reden = 'Nieuwe reden'
WHERE retourId = 2;

-- DELETE
DELETE FROM Retouren
WHERE retourId = 1;

-- Services
-- UPDATE
UPDATE Services
SET servicetijdstip = NOW()
WHERE serviceId = 2;

-- DELETE
DELETE FROM Services
WHERE serviceId = 1;


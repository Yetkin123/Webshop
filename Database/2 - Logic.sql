--  Hier komen procedures, functions, triggers, calls etc

-- Trigger --
-- Als status van factuur, bijvoorbeeld een "Open" factuur, wordt gewijzigd naar "Betaald" dan wordt deze in het Orders-tabel ook omgezet naar "Betaald". --
-- Trigger is terug te vinden onder de Triggers van het Facturen-tabel --
USE `Yerothia`;

DROP TRIGGER IF EXISTS `update_order_status`;
DROP TRIGGER IF EXISTS `set_factuurprijs`;
DROP TRIGGER IF EXISTS `update_order_prices`;
DROP TRIGGER IF EXISTS `update_factuurbedrag`;
DROP PROCEDURE IF EXISTS `update_prijs`;
DROP FUNCTION IF EXISTS `get_most_orders_in_month`;

DELIMITER //

CREATE TRIGGER `update_order_status` AFTER UPDATE ON `Facturen`
FOR EACH ROW

BEGIN
    IF OLD.status <> 'Betaald' AND NEW.status = 'Betaald' THEN
        UPDATE `Orders`
        SET status = 'Betaald'
        WHERE orderId = NEW.orderId;
    END IF;
END;
//

CREATE TRIGGER `set_factuurprijs` BEFORE INSERT ON `Orderregels`
FOR EACH ROW
BEGIN
    DECLARE factuurprijs DECIMAL(10, 2);

    SELECT `Producten`.`prijs` 
        INTO factuurprijs
        FROM `Producten` 
        WHERE `Producten`.`productId` = NEW.productId;
    
    SET NEW.factuurprijs = factuurprijs;
END;

//

CREATE TRIGGER `update_order_prices` AFTER INSERT ON `Orderregels`
FOR EACH ROW
BEGIN
    DECLARE new_total DECIMAL(10, 2);

    -- Bereken de nieuwe totaalprijs voor de bestelling
    SELECT SUM(`aantal` * `factuurprijs`) 
        INTO new_total 
        FROM `Orderregels` 
        WHERE `orderId` = NEW.orderId;

    -- Update de totaalprijs van de bestelling in de Orders tabel
    UPDATE `Orders` SET `totaalprijs` = new_total
    WHERE `orderId` = NEW.orderId;
END;

//

CREATE TRIGGER `update_factuurbedrag` AFTER UPDATE ON `Orders`
FOR EACH ROW
BEGIN
    IF OLD.totaalprijs <> NEW.totaalprijs THEN
        UPDATE `Facturen` 
            SET `factuurbedrag` = NEW.totaalprijs
            WHERE `orderId` = NEW.orderId; 
    END IF;
END;

//

-- Stored procedure --
-- Hiermee kan je de prijs updaten op het moment dat je de productId en de nieuwe prijs doorgeeft wanneer je de procedure uitvoert --
-- Terug te vinden onder het Yerothia datbase en dan Routines --

CREATE PROCEDURE `update_prijs`(
    IN update_productId INT,
    IN update_prijs DECIMAL(10, 2)
)
BEGIN
    -- Update de prijs van het product met de opgegeven productId
    UPDATE Producten SET prijs = update_prijs WHERE productId = update_productId;
    
    -- Geef een melding terug over het bijwerken van de prijs
    SELECT CONCAT('Prijs van product met ID ', update_productId, ' is bijgewerkt naar ', update_prijs) AS Message;
END //

-- CALL update_prijs(p_productId, p_newPrice);

CREATE FUNCTION `get_most_orders_in_month`()
RETURNS INT
BEGIN
    DECLARE month_number INT;
    SELECT MONTH(`orderdatum`) INTO month_number
    FROM `Orders`
    GROUP BY MONTH(`orderdatum`)
    ORDER BY COUNT(*) DESC
    LIMIT 1;
    RETURN month_number;
END //

DELIMITER ;


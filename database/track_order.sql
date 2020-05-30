DROP DATABASE IF EXISTS track_order;

CREATE DATABASE track_order;
USE track_order;

CREATE TABLE users (
    id SMALLINT UNSIGNED AUTO_INCREMENT,
    email VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(50) NOT NULL,
    surname VARCHAR(50) NOT NULL,
    address VARCHAR(255) NOT NULL,
    zip_code SMALLINT UNSIGNED NOT NULL,
    telephone VARCHAR(25) NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE status (
    id SMALLINT UNSIGNED AUTO_INCREMENT,
    title VARCHAR(50) NOT NULL,
    description VARCHAR(255) NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE trackings (
    id SMALLINT UNSIGNED AUTO_INCREMENT,
    courier CHAR(3) NOT NULL CHECK (courier IN ('BRT', 'DHL', 'GLS', 'SDA')),
    status_id SMALLINT UNSIGNED DEFAULT 1,
    PRIMARY KEY(id),
    FOREIGN KEY (status_id) REFERENCES status(id)
);

CREATE TABLE orders (
    id SMALLINT UNSIGNED AUTO_INCREMENT,
    n_items SMALLINT UNSIGNED NOT NULL,
    total_cost SMALLINT UNSIGNED NOT NULL,
    order_date DATE NOT NULL,
    ship_date DATE DEFAULT NULL,
    delivery_date DATE DEFAULT NULL,
    user_id SMALLINT UNSIGNED NOT NULL,
    tracking_id SMALLINT UNSIGNED UNIQUE,
    PRIMARY KEY(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (tracking_id) REFERENCES trackings(id)
);

/* Insert users table, psw SHA512 of name field */
INSERT INTO users VALUES (default, 'admin@gmail.com', '887375DAEC62A9F02D32A63C9E14C7641A9A8A42E4FA8F6590EB928D9744B57BB5057A1D227E4D40EF911AC030590BBCE2BFDB78103FF0B79094CEE8425601F5', 'Admin', 'Manager', 'Via Giove 1, PG, IT', 12344, '1112223334');
INSERT INTO users VALUES (default, 'davide@gmail.com', '1540CCA7AF9E61660137765D40522360EA6A43910E9454BEBD60BCF9CA2B84C271D42FA5218A662946F6A01386E2A0B68C4256E81C41861EE0F769C684E8F393', 'Davide', 'Rossi', 'Via Roma 8, PG, IT', 12345, '1112223335');
INSERT INTO users VALUES (default, 'amir@gmail.com', '6EE891797B565AC9D566E7F869754ECA99C9F968A2F7EAAA3F4D865603BF1B45DFCFFE41B7E4111B707236A404B686A8FC322C580CB16103F17FE790F70AD7EE', 'Amir', 'Russo', 'Via Verdi 16, PG, IT', 12346, '1112223336');
INSERT INTO users VALUES (default, 'sergio@gmail.com', 'D70227332DD986CDCD74FCCDE6E2086060FC25EA0654A46B1262A2FCEB62D6975669051620E61C6126FC1F9370EA12A0F0C5C1A4986457CDF162AB791815E462', 'Sergio', 'Ferrari', 'Via Bianchi 24, PG, IT', 12347, '1112223337');
INSERT INTO users VALUES (default, 'federico@gmail.com', 'EEEBE63454DD55A1D7C0026884962D99021BDB20BE27ED269BAC02ED375A494D3F8215EC968F8D3FCB0B951F4CC44A86B750DBFF825CEF867301788F31196B00', 'Federico', 'Bianchi', 'Via Neri 32, PG, IT', 12348, '1112223338');

/* Insert status table */
INSERT INTO status VALUES (default, 'In lavorazione', 'Preparazione del pacco in magazzino.');
INSERT INTO status VALUES (default, 'Spedito', 'La spedizione è stata assegnata al corriere.');
INSERT INTO status VALUES (default, 'In transito', 'Arrivato presso la sede di consegna.');
INSERT INTO status VALUES (default, 'In consegna', 'Il corriere consegnerà il pacco entro la giornata odierna.');
INSERT INTO status VALUES (default, 'Consegnato', 'Il pacco è stato consegnato correttamente.');
INSERT INTO status VALUES (default, 'Mancata consegna', 'Destinatario non presente nella sede di destinazione.');

/* Insert trackings table*/
INSERT INTO trackings VALUES (default, 'BRT', 1);
INSERT INTO trackings VALUES (default, 'DHL', 2);
INSERT INTO trackings VALUES (default, 'GLS', 3);
INSERT INTO trackings VALUES (default, 'SDA', 4);
INSERT INTO trackings VALUES (default, 'GLS', 5);

/* Insert orders table */
INSERT INTO orders(id, n_items, total_cost, order_date, user_id) VALUES (default, 1, 10.90, '2020-05-17', 2);
INSERT INTO orders(id, n_items, total_cost, order_date, user_id) VALUES (default, 2, 31.60, '2020-05-18', 3);
INSERT INTO orders(id, n_items, total_cost, order_date, user_id) VALUES (default, 3, 15.50, '2020-05-19', 4);
INSERT INTO orders(id, n_items, total_cost, order_date, user_id) VALUES (default, 4, 11.20, '2020-05-20', 5);
INSERT INTO orders VALUES (default, 5, 45.90, '2020-05-21', '2020-05-22', '2020-05-25', 2, 1);
INSERT INTO orders VALUES (default, 6, 129.50, '2020-05-22', '2020-05-23', '2020-05-26', 3, 2);
INSERT INTO orders VALUES (default, 7, 12.40, '2020-05-23', '2020-05-24', '2020-05-27', 4, 3);
INSERT INTO orders VALUES (default, 8, 62.10, '2020-05-24', '2020-05-25', '2020-05-28', 5, 4);

/* Create trigger for fill ship_date and delivery_date*/
DROP TRIGGER IF EXISTS track_order.upd_track_date;

DELIMITER $$
CREATE TRIGGER upd_track_date BEFORE UPDATE ON orders
FOR EACH ROW
BEGIN
        IF NEW.tracking_id IS NOT NULL THEN
            SET NEW.ship_date = CURDATE();
			SET NEW.delivery_date = DATE_ADD(CURDATE(), INTERVAL 3 DAY);
        ELSEIF NEW.tracking_id IS NULL THEN
            SET NEW.ship_date = NULL;
			SET NEW.delivery_date = NULL;
        END IF;
END;$$
DELIMITER ;
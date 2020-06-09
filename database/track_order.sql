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
    total_cost FLOAT UNSIGNED NOT NULL,
    order_date DATE NOT NULL,
    ship_date DATE DEFAULT NULL,
    delivery_date DATE DEFAULT NULL,
    user_id SMALLINT UNSIGNED NOT NULL,
    tracking_id SMALLINT UNSIGNED UNIQUE,
    PRIMARY KEY(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (tracking_id) REFERENCES trackings(id) ON DELETE SET NULL
);

/* Insert users table, PASSWORD_ARGON2I of lowercase(name) field */
INSERT INTO users VALUES (default, 'admin@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$bmJLY1E0eENPL3ZyWWZjVA$Bwb+/DLWTf3v5wKJBIAzjxwoxOc6FiSqQ6XmvbKU+Ns', 'Admin', 'Manager', 'Via Giove 1, PG, IT', 12344, '1112223334');
INSERT INTO users VALUES (default, 'davide@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$QWxtSnpTTlJEOTZSblhLMA$GtALF96vDOyA8ZlE3Qgou6aZz8whEtBPUP6+Rlkov/U', 'Davide', 'Rossi', 'Via Roma 8, PG, IT', 12345, '1112223335');
INSERT INTO users VALUES (default, 'amir@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$ei50QW1FaUdQME9jS0VyYQ$RoJdPFtCbYtmDZeZMORc/iZvL1sXAvYJAZTu1Y3CjYE', 'Amir', 'Russo', 'Via Verdi 16, PG, IT', 12346, '1112223336');
INSERT INTO users VALUES (default, 'sergio@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$QVE3QnUwQUJITlZDN2c4Yg$MMG9njQoZ/Cju8Y2t2at08ZYcSssD9zVYerTOZgqHuc', 'Sergio', 'Ferrari', 'Via Bianchi 24, PG, IT', 12347, '1112223337');
INSERT INTO users VALUES (default, 'federico@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$eGd5Nnpma0lHdWRiS3N6Wg$aGynSm53Z1xXkeDubbLfms/B+8snZptODZ/MS7BIU0g', 'Federico', 'Bianchi', 'Via Neri 32, PG, IT', 12348, '1112223338');

/* Insert status table */
INSERT INTO status VALUES (default, 'In lavorazione', 'Preparazione del pacco in magazzino.');
INSERT INTO status VALUES (default, 'Spedito', 'La spedizione è stata assegnata al corriere.');
INSERT INTO status VALUES (default, 'In transito', 'Arrivato presso la sede di consegna.');
INSERT INTO status VALUES (default, 'In consegna', 'Il corriere consegnerà il pacco entro la giornata odierna.');
INSERT INTO status VALUES (default, 'Consegnato', 'Il pacco è stato consegnato correttamente.');

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
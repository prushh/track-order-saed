/* GET all orders by user_id */
SELECT orders.id, orders.total_cost, orders.order_date
FROM orders
WHERE orders.user_id = <user_id>;

/* GET all orders with tracking_id by user_id */
SELECT orders.id, orders.total_cost, orders.order_date
FROM orders
WHERE orders.user_id = <user_id> AND
      orders.tracking_id IS NOT NULL;

/* GET all orders with tracking_id (admin side) */
SELECT orders.id, orders.total_cost, orders.order_date, orders.ship_date,
       orders.delivery_date, orders.user_id, users.address, users.telephone,
       orders.tracking_id, status.title AS status
FROM orders INNER JOIN users ON orders.user_id = users.id
            INNER JOIN trackings ON orders.tracking_id = trackings.id
            INNER JOIN status ON trackings.status_id = status.id
WHERE orders.tracking_id IS NOT NULL;

/* GET all orders without tracking_id (admin side) */
SELECT orders.id, orders.n_items, orders.total_cost, orders.order_date,
       users.name, users.surname, users.email, users.address
FROM orders INNER JOIN users ON orders.user_id = users.id
WHERE orders.tracking_id IS NULL;

/* GET all orders without tracking_id by user_id (admin side) */
SELECT orders.id, orders.total_cost, orders.order_date, orders.user_id,
       users.address, users.telephone
FROM orders INNER JOIN users ON orders.user_id = users.id
WHERE orders.user_id = <user_id> AND
      orders.tracking_id IS NULL;

/* GET order details by order_id */
SELECT orders.id, orders.n_items, orders.n_items, orders.order_date,
       orders.ship_date, orders.delivery_date, orders.tracking_id,
       status.title, status.description
FROM orders INNER JOIN trackings ON orders.tracking_id = trackings.id
            INNER JOIN status ON trackings.status_id = status.id
WHERE orders.id = <order_id>;

/* GET all title for status (fill combobox) */
SELECT status.id, status.title, status.description
FROM status;

/* UPDATE status for trackings by tracking_id and status_id */
UPDATE trackings
SET status_id = <status_id>
WHERE trackings.id = <tracking_id>;

/* INSERT new tracking by order_id and tracking_id */
INSERT INTO trackings VALUES (default, <courier>, 1)
UPDATE orders
SET orders.tracking_id = (
    /* GET last id in trackings */
    SELECT MAX(trackings.id)
    FROM trackings
    )
WHERE orders.id = <order_id>;

/* DELETE tracking_id by order_id and tracking_id */
UPDATE orders
SET orders.tracking_id = NULL
WHERE orders.id = <order_id> AND
      orders.tracking_id = <tracking_id>;
/* DEL tracking by tracking_id */
DELETE FROM trackings WHERE trackings.id = <tracking_id>;

/* Login by email */
SELECT users.name, users.surname, users.email, users.password
FROM users
WHERE users.email = <email>;
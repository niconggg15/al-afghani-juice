<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Drop existing procedures/functions/triggers if they exist
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_create_order');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_update_stock');
        DB::unprepared('DROP FUNCTION IF EXISTS fn_calculate_order_total');
        DB::unprepared('DROP FUNCTION IF EXISTS fn_get_low_stock_products');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_after_order_item_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_after_order_item_delete');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_before_order_update');

        // Stored Procedure: Create order with transaction (Tanpa PPN)
        DB::unprepared('
            CREATE PROCEDURE sp_create_order(
                IN p_customer_name VARCHAR(255),
                IN p_customer_phone VARCHAR(20),
                IN p_payment_method ENUM("cash", "qris"),
                IN p_items JSON,
                IN p_notes TEXT,
                OUT p_order_id BIGINT,
                OUT p_order_number VARCHAR(50)
            )
            BEGIN
                DECLARE v_subtotal DECIMAL(10,2) DEFAULT 0;
                DECLARE v_tax DECIMAL(10,2) DEFAULT 0;
                DECLARE v_total DECIMAL(10,2) DEFAULT 0;
                DECLARE v_order_number VARCHAR(50);
                DECLARE v_item_count INT DEFAULT 0;
                DECLARE v_i INT DEFAULT 0;
                DECLARE v_product_id BIGINT;
                DECLARE v_quantity INT;
                DECLARE v_price DECIMAL(10,2);
                DECLARE v_subtotal_item DECIMAL(10,2);
                DECLARE v_stock INT;
                DECLARE EXIT HANDLER FOR SQLEXCEPTION
                BEGIN
                    ROLLBACK;
                    RESIGNAL;
                END;

                START TRANSACTION;

                SET v_order_number = CONCAT("KJ", DATE_FORMAT(NOW(), "%Y%m%d"), LPAD(
                    (SELECT COALESCE(MAX(CAST(SUBSTRING(order_number, 13) AS UNSIGNED)), 0) + 1
                     FROM orders
                     WHERE order_number LIKE CONCAT("KJ", DATE_FORMAT(NOW(), "%Y%m%d"), "%")
                    ), 4, "0"));

                INSERT INTO orders (order_number, customer_name, customer_phone, payment_method, status, notes, created_at, updated_at)
                VALUES (v_order_number, p_customer_name, p_customer_phone, p_payment_method, "pending", p_notes, NOW(), NOW());

                SET p_order_id = LAST_INSERT_ID();
                SET p_order_number = v_order_number;
                SET v_item_count = JSON_LENGTH(p_items);

                WHILE v_i < v_item_count DO
                    SET v_product_id = JSON_EXTRACT(p_items, CONCAT("$[", v_i, "].product_id"));
                    SET v_quantity = JSON_EXTRACT(p_items, CONCAT("$[", v_i, "].quantity"));
                    SET v_price = JSON_EXTRACT(p_items, CONCAT("$[", v_i, "].price"));

                    SELECT stock INTO v_stock FROM products WHERE id = v_product_id FOR UPDATE;

                    IF v_stock < v_quantity THEN
                        SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "Insufficient stock";
                    END IF;

                    SET v_subtotal_item = v_price * v_quantity;
                    SET v_subtotal = v_subtotal + v_subtotal_item;

                    INSERT INTO order_items (order_id, product_id, quantity, price, subtotal, created_at, updated_at)
                    VALUES (p_order_id, v_product_id, v_quantity, v_price, v_subtotal_item, NOW(), NOW());

                    UPDATE products SET stock = stock - v_quantity WHERE id = v_product_id;

                    SET v_i = v_i + 1;
                END WHILE;

                SET v_tax = 0;
                SET v_total = v_subtotal;

                UPDATE orders SET subtotal = v_subtotal, tax = v_tax, total = v_total WHERE id = p_order_id;

                COMMIT;
            END
        ');

        // Stored Procedure: Update stock
        DB::unprepared('
            CREATE PROCEDURE sp_update_stock(
                IN p_product_id BIGINT,
                IN p_quantity INT,
                IN p_operation ENUM("add", "subtract")
            )
            BEGIN
                DECLARE v_current_stock INT;

                START TRANSACTION;

                SELECT stock INTO v_current_stock FROM products WHERE id = p_product_id FOR UPDATE;

                IF p_operation = "add" THEN
                    UPDATE products SET stock = stock + p_quantity WHERE id = p_product_id;
                ELSEIF p_operation = "subtract" THEN
                    IF v_current_stock < p_quantity THEN
                        SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "Insufficient stock";
                    END IF;
                    UPDATE products SET stock = stock - p_quantity WHERE id = p_product_id;
                END IF;

                COMMIT;
            END
        ');

        // Function: Calculate order total (Tanpa PPN)
        DB::unprepared('
            CREATE FUNCTION fn_calculate_order_total(p_order_id BIGINT)
            RETURNS DECIMAL(10,2)
            READS SQL DATA
            DETERMINISTIC
            BEGIN
                DECLARE v_total DECIMAL(10,2) DEFAULT 0;

                SELECT COALESCE(SUM(subtotal), 0) INTO v_total
                FROM order_items
                WHERE order_id = p_order_id;

                RETURN v_total;
            END
        ');

        // Function: Get low stock products
        DB::unprepared('
            CREATE FUNCTION fn_get_low_stock_products()
            RETURNS JSON
            READS SQL DATA
            DETERMINISTIC
            BEGIN
                DECLARE v_result JSON;

                SELECT JSON_ARRAYAGG(
                    JSON_OBJECT(
                        "id", id,
                        "name", name,
                        "stock", stock,
                        "min_stock", min_stock
                    )
                ) INTO v_result
                FROM products
                WHERE stock <= min_stock AND is_active = 1;

                RETURN COALESCE(v_result, JSON_ARRAY());
            END
        ');

        // Trigger: After order item insert - update order total (Tanpa PPN)
        DB::unprepared('
            CREATE TRIGGER trg_after_order_item_insert
            AFTER INSERT ON order_items
            FOR EACH ROW
            BEGIN
                DECLARE v_subtotal DECIMAL(10,2);
                DECLARE v_tax DECIMAL(10,2);
                DECLARE v_total DECIMAL(10,2);

                SELECT COALESCE(SUM(subtotal), 0) INTO v_subtotal
                FROM order_items
                WHERE order_id = NEW.order_id;

                SET v_tax = 0;
                SET v_total = v_subtotal;

                UPDATE orders
                SET subtotal = v_subtotal, tax = v_tax, total = v_total, updated_at = NOW()
                WHERE id = NEW.order_id;
            END
        ');

        // Trigger: After order item delete - update order total (Tanpa PPN)
        DB::unprepared('
            CREATE TRIGGER trg_after_order_item_delete
            AFTER DELETE ON order_items
            FOR EACH ROW
            BEGIN
                DECLARE v_subtotal DECIMAL(10,2);
                DECLARE v_tax DECIMAL(10,2);
                DECLARE v_total DECIMAL(10,2);

                SELECT COALESCE(SUM(subtotal), 0) INTO v_subtotal
                FROM order_items
                WHERE order_id = OLD.order_id;

                SET v_tax = 0;
                SET v_total = v_subtotal;

                UPDATE orders
                SET subtotal = v_subtotal, tax = v_tax, total = v_total, updated_at = NOW()
                WHERE id = OLD.order_id;
            END
        ');

        // Trigger: Before order update - handle status changes
        DB::unprepared('
            CREATE TRIGGER trg_before_order_update
            BEFORE UPDATE ON orders
            FOR EACH ROW
            BEGIN
                IF NEW.status = "confirmed" AND OLD.status != "confirmed" THEN
                    SET NEW.confirmed_at = NOW();
                END IF;

                IF NEW.status = "completed" AND OLD.status != "completed" THEN
                    SET NEW.completed_at = NOW();
                END IF;

                IF NEW.status = "cancelled" AND OLD.status != "cancelled" THEN
                    -- Restore stock for cancelled order
                    UPDATE products p
                    JOIN order_items oi ON p.id = oi.product_id
                    SET p.stock = p.stock + oi.quantity
                    WHERE oi.order_id = NEW.id;
                END IF;
            END
        ');
    }

    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_create_order');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_update_stock');
        DB::unprepared('DROP FUNCTION IF EXISTS fn_calculate_order_total');
        DB::unprepared('DROP FUNCTION IF EXISTS fn_get_low_stock_products');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_after_order_item_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_after_order_item_delete');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_before_order_update');
    }
};
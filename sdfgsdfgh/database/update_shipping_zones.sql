-- Add zone_description column to shipping_zones table
ALTER TABLE shipping_zones ADD COLUMN zone_description TEXT AFTER zone_name;

-- Update shipping_rates table structure
ALTER TABLE shipping_rates 
    DROP COLUMN min_order_amount,
    DROP COLUMN max_order_amount,
    DROP COLUMN shipping_cost,
    DROP COLUMN estimated_days,
    DROP COLUMN status,
    ADD COLUMN rate_amount DECIMAL(10,2) NOT NULL AFTER zone_id,
    ADD COLUMN min_weight DECIMAL(10,2) NOT NULL AFTER rate_amount,
    ADD COLUMN max_weight DECIMAL(10,2) AFTER min_weight; 
-- Add report_submit_date column to works table
-- Run this if you prefer raw SQL over Laravel migration
-- Usage: mysql -u your_user -p your_database < database/add_report_submit_date.sql

ALTER TABLE works ADD COLUMN report_submit_date DATE NULL AFTER is_printed;

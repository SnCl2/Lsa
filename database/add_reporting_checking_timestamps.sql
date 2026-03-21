-- Add timestamp columns for Reporting and Checking time tracking
-- Run this SQL manually (e.g., via phpMyAdmin or mysql CLI)
-- Execute each statement one at a time. If AFTER fails, use the alternative below.

ALTER TABLE works ADD COLUMN reporting_started_at TIMESTAMP NULL;
ALTER TABLE works ADD COLUMN reporting_ended_at TIMESTAMP NULL;
ALTER TABLE works ADD COLUMN checking_started_at TIMESTAMP NULL;
ALTER TABLE works ADD COLUMN checking_ended_at TIMESTAMP NULL;

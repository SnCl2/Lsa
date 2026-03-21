/*
  Reference only. Run this SQL manually after backup.
  Do NOT run "php artisan migrate" for billing columns.
*/

ALTER TABLE works
  ADD COLUMN is_billing_done TINYINT(1) NOT NULL DEFAULT 0,
  ADD COLUMN billing_done_at TIMESTAMP NULL,
  ADD COLUMN billing_done_by BIGINT UNSIGNED NULL,
  ADD COLUMN invoice_number VARCHAR(255) NULL,
  ADD COLUMN invoice_date DATE NULL,
  ADD COLUMN invoice_amount DECIMAL(12,2) NULL,
  ADD COLUMN amount_without_gst DECIMAL(12,2) NULL,
  ADD COLUMN gst_amount DECIMAL(12,2) NULL;

ALTER TABLE works
  ADD CONSTRAINT fk_works_billing_done_by
  FOREIGN KEY (billing_done_by) REFERENCES users(id) ON DELETE SET NULL;

/*
  Add Accountant role if not exists:
  INSERT INTO roles (name) VALUES ('Accountant');
*/

-- Add soft deletes to users table
ALTER TABLE users ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL;

-- Create index for better performance when querying non-deleted users
CREATE INDEX idx_users_deleted_at ON users (deleted_at);
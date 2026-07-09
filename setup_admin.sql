-- Super Admin Setup SQL Script
-- Run this in phpMyAdmin or MySQL directly

-- Create Super Admin Role (if not exists)
INSERT INTO roles (name, guard_name, created_at, updated_at)
SELECT 'super_admin', 'web', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM roles WHERE name = 'super_admin');

-- Create Administrator Role (if not exists)
INSERT INTO roles (name, guard_name, created_at, updated_at)
SELECT 'administrator', 'web', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM roles WHERE name = 'administrator');

-- Get the role ID
SET @role_id = (SELECT id FROM roles WHERE name = 'super_admin' LIMIT 1);

-- Insert or Update Super Admin User
INSERT INTO users (name, email, password, email_verified_at, created_at, updated_at)
VALUES ('Super Admin', 'admin@clothes.local', '$2y$10$QnnCczr7nSe0Y5g7lxGE/eOYV18FeW4Kp60r2Z5cQ3Z7Wb1V1K.nW', NOW(), NOW(), NOW())
ON DUPLICATE KEY UPDATE
  password = '$2y$10$QnnCczr7nSe0Y5g7lxGE/eOYV18FeW4Kp60r2Z5cQ3Z7Wb1V1K.nW',
  email_verified_at = NOW(),
  updated_at = NOW();

-- Get the user ID
SET @user_id = (SELECT id FROM users WHERE email = 'admin@clothes.local' LIMIT 1);

-- Assign role to user (if not already assigned)
INSERT INTO model_has_roles (role_id, model_type, model_id)
VALUES (@role_id, 'App\\Models\\User', @user_id)
ON DUPLICATE KEY UPDATE
  role_id = @role_id;

-- Confirm
SELECT 'Super Admin Setup Complete!' as Status;
SELECT CONCAT('Email: ', email, ' | Password: admin@123 | Role: super_admin') as Credentials 
FROM users WHERE email = 'admin@clothes.local';

create database sampiSignUp;
use sampiSignUp;

CREATE TABLE signUp (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_email VARCHAR(100) NOT NULL UNIQUE,
    user_password VARCHAR(255) NOT NULL,
    user_role VARCHAR(50) DEFAULT 'user',  -- Values: 'admin', 'user', etc.
    user_name VARCHAR(50)
);
INSERT INTO signUp (user_email, user_password, user_role)
VALUES (
  'inquiry.sampi@gmail.com',
  '$2y$10$8oj5pUnRPEqSjNTdegnUU.PsCxSjGdt.DzfXbZk7.S.hxbn6uJUVa',
  'admin'
);


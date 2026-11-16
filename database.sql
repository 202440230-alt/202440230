CREATE DATABASE IF NOT EXISTS savespace CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE savespace;

CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL AUTO_INCREMENT,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    contact VARCHAR(11) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(10) NOT NULL DEFAULT 'customer',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS units (
    id INT NOT NULL AUTO_INCREMENT,
    code VARCHAR(10) NOT NULL,
    size VARCHAR(20) NOT NULL,
    rate INT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'available',
    PRIMARY KEY (id),
    UNIQUE KEY code (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS reservations (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    unit_id INT NOT NULL,
    movein DATE NOT NULL,
    months INT NOT NULL,
    notes VARCHAR(300) NOT NULL DEFAULT '',
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (unit_id) REFERENCES units(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT IGNORE INTO units (code, size, rate) VALUES
('A-05', 'Small Locker', 500),
('A-06', 'Small Locker', 500),
('A-07', 'Small Locker', 500),
('B-10', 'Medium Room', 1500),
('B-11', 'Medium Room', 1500),
('B-12', 'Medium Room', 1500),
('C-19', 'Large Room', 3000),
('C-20', 'Large Room', 3000),
('C-21', 'Large Room', 3000);

INSERT IGNORE INTO users (fullname, email, contact, password, role) VALUES
('SaveSpace Owner', 'owner@savespace.com', '09950939228', '$2y$10$ahwl6GtufhkWLh8IpZMbnuenqWaHIWpZHhMDTfjAB7TR/WS3zvKkG', 'admin');

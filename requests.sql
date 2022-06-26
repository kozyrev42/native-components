CREATE TABLE `level-two-users` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(255) NOT NULL,
    `username` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `group_id` VARCHAR(255) NOT NULL DEFAULT 1,
    PRIMARY KEY (id)
) default charset utf8mb4;

CREATE TABLE `user_sessions` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `user_id` VARCHAR(255) NOT NULL,
    `hash` VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
) default charset utf8mb4;

CREATE TABLE `groups` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `permissions` VARCHAR(255),
    PRIMARY KEY (id)
) default charset utf8mb4;
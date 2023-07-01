CREATE TABLE IF NOT EXISTS `auth_token` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED NOT NULL,
    `text` VARCHAR(255) UNIQUE NOT NULL,
    `device` VARCHAR(255) NOT NULL DEFAULT '',
    `creation_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    CONSTRAINT `pk_auth_token` PRIMARY KEY (`id`),
    CONSTRAINT `fk_auth_token_user_id` FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`)
) ENGINE={Framework4{DB_ENGINE}} 
DEFAULT CHARSET={Framework4{DB_CHARSET}} COLLATE={Framework4{DB_COLLATION}};

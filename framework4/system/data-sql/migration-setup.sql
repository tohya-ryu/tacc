CREATE TABLE IF NOT EXISTS `framework_migration` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `filename`     VARCHAR(255) UNIQUE NOT NULL,
    `version`      VARCHAR(255) NOT NULL,
    CONSTRAINT `pk_framework_migration` PRIMARY KEY (`id`)
) ENGINE={Framework4{DB_ENGINE}} 
DEFAULT CHARSET={Framework4{DB_CHARSET}} COLLATE={Framework4{DB_COLLATION}};

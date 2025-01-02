<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241204085715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial DB migration - creates tblProductData';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            <<<SQL
        CREATE TABLE product_data (
          id INT UNSIGNED NOT NULL AUTO_INCREMENT,
          name VARCHAR(50) NOT NULL,
          description VARCHAR(255) NOT NULL,
          code VARCHAR(10) NOT NULL,
          created_at DATETIME DEFAULT NULL,
          discontinued_at DATETIME DEFAULT NULL,
          updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (id),
          UNIQUE KEY (code)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores product data';
        SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE product_data');
    }
}

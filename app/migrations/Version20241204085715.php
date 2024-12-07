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
            CREATE TABLE tblProductData (
              intProductDataId INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
              strProductName VARCHAR(50) NOT NULL,
              strProductDesc VARCHAR(255) NOT NULL,
              strProductCode VARCHAR(10) NOT NULL,
              dtmAdded DATETIME DEFAULT NULL,
              dtmDiscontinued DATETIME DEFAULT NULL,
              stmTimestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              PRIMARY KEY (intProductDataId),
              UNIQUE KEY (strProductCode)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Stores product data';
        SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE tblProductData');
    }
}

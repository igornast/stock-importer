<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241209152947 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add stock, money amount and currency code to the tblProductData table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
        ALTER TABLE product_data 
            ADD stock INT DEFAULT NULL, 
            ADD amount_in_cents INT DEFAULT NULL, 
            ADD currency_code VARCHAR(3) DEFAULT NULL
    SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<SQL
        ALTER TABLE product_data 
            DROP stock,
            DROP amount_in_cents,
            DROP currency_code
    SQL);
    }
}

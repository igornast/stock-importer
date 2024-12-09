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
        $this->addSql('ALTER TABLE tblProductData ADD intProductStock INT DEFAULT NULL, ADD intMoneyAmount INT DEFAULT NULL, ADD strMoneyCode VARCHAR(3) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tblProductData  DROP intProductStock, DROP intMoneyAmount, DROP strMoneyCode');
    }
}

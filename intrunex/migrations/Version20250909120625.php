<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250909120625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE asset_discovery_asset ADD COLUMN url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE asset_discovery_asset ADD COLUMN domain VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__asset_discovery_asset AS SELECT id, name, ip_address, type, status, description FROM asset_discovery_asset');
        $this->addSql('DROP TABLE asset_discovery_asset');
        $this->addSql('CREATE TABLE asset_discovery_asset (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, ip_address VARCHAR(45) DEFAULT NULL, type VARCHAR(50) NOT NULL, status VARCHAR(50) NOT NULL, description CLOB DEFAULT NULL)');
        $this->addSql('INSERT INTO asset_discovery_asset (id, name, ip_address, type, status, description) SELECT id, name, ip_address, type, status, description FROM __temp__asset_discovery_asset');
        $this->addSql('DROP TABLE __temp__asset_discovery_asset');
    }
}

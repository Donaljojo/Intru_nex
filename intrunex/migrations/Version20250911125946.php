<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250911125946 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add user_id column nullable and backfill user_id for assets';
    }

    public function up(Schema $schema): void
    {
        // Create a temporary table without user_id column
        $this->addSql('CREATE TEMPORARY TABLE __temp__asset_discovery_asset AS SELECT id, name, ip_address, type, status, description, url, domain FROM asset_discovery_asset');
        
        // Drop the existing asset_discovery_asset table
        $this->addSql('DROP TABLE asset_discovery_asset');
        
        // Create new table with user_id column nullable
        $this->addSql('CREATE TABLE asset_discovery_asset (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            user_id INTEGER DEFAULT NULL,
            name VARCHAR(255) NOT NULL,
            ip_address VARCHAR(45) DEFAULT NULL,
            type VARCHAR(50) NOT NULL,
            status VARCHAR(50) NOT NULL,
            description CLOB DEFAULT NULL,
            url VARCHAR(255) DEFAULT NULL,
            domain VARCHAR(255) DEFAULT NULL,
            CONSTRAINT FK_665E49E3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        )');
        
        // Copy old data into new table (user_id is null for now)
        $this->addSql('INSERT INTO asset_discovery_asset (id, name, ip_address, type, status, description, url, domain) SELECT id, name, ip_address, type, status, description, url, domain FROM __temp__asset_discovery_asset');
        
        // Backfill user_id with default valid user id (replace 1 with correct user id)
        $this->addSql('UPDATE asset_discovery_asset SET user_id = 1 WHERE user_id IS NULL');
        
        // Drop temporary table after copying data
        $this->addSql('DROP TABLE __temp__asset_discovery_asset');

        // Create index for performance on user_id
        $this->addSql('CREATE INDEX IDX_665E49E3A76ED395 ON asset_discovery_asset (user_id)');
    }

    public function down(Schema $schema): void
    {
        // Revert schema to original without user_id column
        $this->addSql('CREATE TEMPORARY TABLE __temp__asset_discovery_asset AS SELECT id, name, ip_address, url, domain, type, status, description FROM asset_discovery_asset');
        $this->addSql('DROP TABLE asset_discovery_asset');
        $this->addSql('CREATE TABLE asset_discovery_asset (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            name VARCHAR(255) NOT NULL,
            ip_address VARCHAR(45) DEFAULT NULL,
            url VARCHAR(255) DEFAULT NULL,
            domain VARCHAR(255) DEFAULT NULL,
            type VARCHAR(50) NOT NULL,
            status VARCHAR(50) NOT NULL,
            description CLOB DEFAULT NULL
        )');
        $this->addSql('INSERT INTO asset_discovery_asset (id, name, ip_address, url, domain, type, status, description) SELECT id, name, ip_address, url, domain, type, status, description FROM __temp__asset_discovery_asset');
        $this->addSql('DROP TABLE __temp__asset_discovery_asset');
    }
}




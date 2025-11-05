<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251105100000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add isMonitored field to asset_discovery_asset table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE asset_discovery_asset ADD COLUMN is_monitored BOOLEAN NOT NULL DEFAULT 1');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE __temp__asset_discovery_asset AS SELECT id, user_asset_number, user_id, name, ip_address, url, domain, type, status, description, operating_system, open_ports, last_profiled_at, last_vulnerability_scan_at FROM asset_discovery_asset');
        $this->addSql('DROP TABLE asset_discovery_asset');
        $this->addSql('CREATE TABLE asset_discovery_asset (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_asset_number INTEGER DEFAULT NULL, user_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, ip_address VARCHAR(45) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, domain VARCHAR(255) DEFAULT NULL, type VARCHAR(50) NOT NULL, status VARCHAR(50) NOT NULL, description CLOB DEFAULT NULL, operating_system VARCHAR(255) DEFAULT NULL, open_ports CLOB DEFAULT NULL --(DC2Type:json)
        , last_profiled_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , last_vulnerability_scan_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_EASSETDISCOVERY_ASSET_USER FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO asset_discovery_asset (id, user_asset_number, user_id, name, ip_address, url, domain, type, status, description, operating_system, open_ports, last_profiled_at, last_vulnerability_scan_at) SELECT id, user_asset_number, user_id, name, ip_address, url, domain, type, status, description, operating_system, open_ports, last_profiled_at, last_vulnerability_scan_at FROM __temp__asset_discovery_asset');
        $this->addSql('DROP TABLE __temp__asset_discovery_asset');
        $this->addSql('CREATE INDEX IDX_EASSETDISCOVERY_ASSET_USER_ID ON asset_discovery_asset (user_id)');
    }
}

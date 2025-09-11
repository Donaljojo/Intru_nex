<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250911132421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__asset_discovery_asset AS SELECT id, user_id, name, ip_address, type, status, description, url, domain FROM asset_discovery_asset');
        $this->addSql('DROP TABLE asset_discovery_asset');
        $this->addSql('CREATE TABLE asset_discovery_asset (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, ip_address VARCHAR(45) DEFAULT NULL, type VARCHAR(50) NOT NULL, status VARCHAR(50) NOT NULL, description CLOB DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, domain VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_665E49E3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO asset_discovery_asset (id, user_id, name, ip_address, type, status, description, url, domain) SELECT id, user_id, name, ip_address, type, status, description, url, domain FROM __temp__asset_discovery_asset');
        $this->addSql('DROP TABLE __temp__asset_discovery_asset');
        $this->addSql('CREATE INDEX IDX_665E49E3A76ED395 ON asset_discovery_asset (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__asset_discovery_asset AS SELECT id, user_id, name, ip_address, url, domain, type, status, description FROM asset_discovery_asset');
        $this->addSql('DROP TABLE asset_discovery_asset');
        $this->addSql('CREATE TABLE asset_discovery_asset (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, ip_address VARCHAR(45) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, domain VARCHAR(255) DEFAULT NULL, type VARCHAR(50) NOT NULL, status VARCHAR(50) NOT NULL, description CLOB DEFAULT NULL, CONSTRAINT FK_665E49E3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO asset_discovery_asset (id, user_id, name, ip_address, url, domain, type, status, description) SELECT id, user_id, name, ip_address, url, domain, type, status, description FROM __temp__asset_discovery_asset');
        $this->addSql('DROP TABLE __temp__asset_discovery_asset');
        $this->addSql('CREATE INDEX IDX_665E49E3A76ED395 ON asset_discovery_asset (user_id)');
    }
}

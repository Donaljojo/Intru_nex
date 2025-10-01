<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251001184510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE scan_job (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, asset_id INTEGER NOT NULL, status VARCHAR(255) NOT NULL, started_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , finished_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , scanner VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_8FFE2CAF5DA1941 FOREIGN KEY (asset_id) REFERENCES asset_discovery_asset (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_8FFE2CAF5DA1941 ON scan_job (asset_id)');
        $this->addSql('DROP TABLE scan_management_scan_job');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE scan_management_scan_job (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, asset_id INTEGER NOT NULL, status VARCHAR(20) NOT NULL COLLATE "BINARY", started_at DATETIME DEFAULT NULL, completed_at DATETIME DEFAULT NULL, error_message CLOB DEFAULT NULL COLLATE "BINARY", result CLOB DEFAULT NULL COLLATE "BINARY", CONSTRAINT FK_244D6245DA1941 FOREIGN KEY (asset_id) REFERENCES asset_discovery_asset (id) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_244D6245DA1941 ON scan_management_scan_job (asset_id)');
        $this->addSql('DROP TABLE scan_job');
    }
}

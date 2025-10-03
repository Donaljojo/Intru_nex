<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251003132734 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE scan_job ADD COLUMN error_message CLOB DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__scan_job AS SELECT id, asset_id, status, started_at, finished_at, scanner FROM scan_job');
        $this->addSql('DROP TABLE scan_job');
        $this->addSql('CREATE TABLE scan_job (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, asset_id INTEGER NOT NULL, status VARCHAR(255) NOT NULL, started_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , finished_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , scanner VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_8FFE2CAF5DA1941 FOREIGN KEY (asset_id) REFERENCES asset_discovery_asset (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO scan_job (id, asset_id, status, started_at, finished_at, scanner) SELECT id, asset_id, status, started_at, finished_at, scanner FROM __temp__scan_job');
        $this->addSql('DROP TABLE __temp__scan_job');
        $this->addSql('CREATE INDEX IDX_8FFE2CAF5DA1941 ON scan_job (asset_id)');
    }
}

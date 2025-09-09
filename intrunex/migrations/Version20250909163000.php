<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250909163000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update foreign key on asset_vulnerability_vulnerability.asset_id to use ON DELETE CASCADE';
    }

    public function up(Schema $schema): void
    {
        // Disable foreign keys temporarily
        $this->addSql('PRAGMA foreign_keys=off;');

        // Rename existing table
        $this->addSql('ALTER TABLE asset_vulnerability_vulnerability RENAME TO old_asset_vulnerability_vulnerability;');

        // Create new table with ON DELETE CASCADE on asset_id foreign key
        $this->addSql('CREATE TABLE asset_vulnerability_vulnerability (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            cve_id VARCHAR(100) NOT NULL,
            description CLOB NOT NULL,
            severity VARCHAR(50) NOT NULL,
            discovered_at DATETIME NOT NULL,
            status VARCHAR(50) NOT NULL,
            asset_id INTEGER NOT NULL,
            FOREIGN KEY(asset_id) REFERENCES asset_discovery_asset(id) ON DELETE CASCADE
        );');

        // Copy data from old to new table
        $this->addSql('INSERT INTO asset_vulnerability_vulnerability (id, cve_id, description, severity, discovered_at, status, asset_id)
            SELECT id, cve_id, description, severity, discovered_at, status, asset_id FROM old_asset_vulnerability_vulnerability;');

        // Drop old table
        $this->addSql('DROP TABLE old_asset_vulnerability_vulnerability;');

        // Re-enable foreign keys
        $this->addSql('PRAGMA foreign_keys=on;');
    }

    public function down(Schema $schema): void
    {
        // To revert, repeat above steps without ON DELETE CASCADE or leave empty for manual operations
    }
}

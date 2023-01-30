<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230125201124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE request ADD COLUMN date DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__request AS SELECT id, visitor_id, page_id FROM request');
        $this->addSql('DROP TABLE request');
        $this->addSql('CREATE TABLE request (id VARCHAR(32) NOT NULL, visitor_id VARCHAR(32) DEFAULT NULL, page_id VARCHAR(32) DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_3B978F9F70BEE6D FOREIGN KEY (visitor_id) REFERENCES visitor (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_3B978F9FC4663E4 FOREIGN KEY (page_id) REFERENCES page (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO request (id, visitor_id, page_id) SELECT id, visitor_id, page_id FROM __temp__request');
        $this->addSql('DROP TABLE __temp__request');
        $this->addSql('CREATE INDEX IDX_3B978F9F70BEE6D ON request (visitor_id)');
        $this->addSql('CREATE INDEX IDX_3B978F9FC4663E4 ON request (page_id)');
    }
}

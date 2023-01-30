<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230129161207 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__request AS SELECT id, visitor_id, page_id, date FROM request');
        $this->addSql('DROP TABLE request');
        $this->addSql('CREATE TABLE request (id VARCHAR(32) NOT NULL, visitor_id VARCHAR(32) DEFAULT NULL, page_id VARCHAR(32) DEFAULT NULL, date DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , PRIMARY KEY(id), CONSTRAINT FK_3B978F9F70BEE6D FOREIGN KEY (visitor_id) REFERENCES visitor (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_3B978F9FC4663E4 FOREIGN KEY (page_id) REFERENCES page (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO request (id, visitor_id, page_id, date) SELECT id, visitor_id, page_id, date FROM __temp__request');
        $this->addSql('DROP TABLE __temp__request');
        $this->addSql('CREATE INDEX IDX_3B978F9FC4663E4 ON request (page_id)');
        $this->addSql('CREATE INDEX IDX_3B978F9F70BEE6D ON request (visitor_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, roles, password FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id VARCHAR(32) NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , hashed_password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO user (id, email, roles, hashed_password) SELECT id, email, roles, password FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__request AS SELECT id, visitor_id, page_id, date FROM request');
        $this->addSql('DROP TABLE request');
        $this->addSql('CREATE TABLE request (id VARCHAR(32) NOT NULL, visitor_id VARCHAR(32) DEFAULT NULL, page_id VARCHAR(32) DEFAULT NULL, date DATETIME NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_3B978F9F70BEE6D FOREIGN KEY (visitor_id) REFERENCES visitor (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_3B978F9FC4663E4 FOREIGN KEY (page_id) REFERENCES page (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO request (id, visitor_id, page_id, date) SELECT id, visitor_id, page_id, date FROM __temp__request');
        $this->addSql('DROP TABLE __temp__request');
        $this->addSql('CREATE INDEX IDX_3B978F9F70BEE6D ON request (visitor_id)');
        $this->addSql('CREATE INDEX IDX_3B978F9FC4663E4 ON request (page_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, roles, hashed_password FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id VARCHAR(32) NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO user (id, email, roles, password) SELECT id, email, roles, hashed_password FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }
}

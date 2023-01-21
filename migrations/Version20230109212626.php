<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230109212626 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE area (id VARCHAR(32) NOT NULL, number VARCHAR(3) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE browser (id VARCHAR(32) NOT NULL, name VARCHAR(20) NOT NULL, version VARCHAR(10) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE country (id VARCHAR(32) NOT NULL, short_name VARCHAR(5) NOT NULL, full_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE departement (id VARCHAR(32) NOT NULL, area_id VARCHAR(32) DEFAULT NULL, number VARCHAR(3) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_C1765B63BD0F409C FOREIGN KEY (area_id) REFERENCES area (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_C1765B63BD0F409C ON departement (area_id)');
        $this->addSql('CREATE TABLE langage (id VARCHAR(32) NOT NULL, name VARCHAR(20) NOT NULL, short_name VARCHAR(3) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE origin (id VARCHAR(32) NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE page (id VARCHAR(32) NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE request (id VARCHAR(32) NOT NULL, visitor_id VARCHAR(32) DEFAULT NULL, page_id VARCHAR(32) DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_3B978F9F70BEE6D FOREIGN KEY (visitor_id) REFERENCES visitor (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_3B978F9FC4663E4 FOREIGN KEY (page_id) REFERENCES page (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_3B978F9F70BEE6D ON request (visitor_id)');
        $this->addSql('CREATE INDEX IDX_3B978F9FC4663E4 ON request (page_id)');
        $this->addSql('CREATE TABLE site (id VARCHAR(32) NOT NULL, name VARCHAR(255) NOT NULL, date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE site_status (id VARCHAR(32) NOT NULL, user_id VARCHAR(32) DEFAULT NULL, site_id VARCHAR(32) DEFAULT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_BAECB43A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_BAECB43F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_BAECB43A76ED395 ON site_status (user_id)');
        $this->addSql('CREATE INDEX IDX_BAECB43F6BD1646 ON site_status (site_id)');
        $this->addSql('CREATE TABLE system (id VARCHAR(32) NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user (id VARCHAR(32) NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE TABLE visitor (id VARCHAR(32) NOT NULL, site_id VARCHAR(32) DEFAULT NULL, system_id VARCHAR(32) DEFAULT NULL, langage_id VARCHAR(32) DEFAULT NULL, origin_id VARCHAR(32) DEFAULT NULL, browser_id VARCHAR(32) DEFAULT NULL, country_id VARCHAR(32) DEFAULT NULL, departement_id VARCHAR(32) DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_CAE5E19FF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_CAE5E19FD0952FA5 FOREIGN KEY (system_id) REFERENCES system (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_CAE5E19F957BB53C FOREIGN KEY (langage_id) REFERENCES langage (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_CAE5E19F56A273CC FOREIGN KEY (origin_id) REFERENCES origin (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_CAE5E19F4C9BF8BD FOREIGN KEY (browser_id) REFERENCES browser (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_CAE5E19FF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_CAE5E19FCCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_CAE5E19FF6BD1646 ON visitor (site_id)');
        $this->addSql('CREATE INDEX IDX_CAE5E19FD0952FA5 ON visitor (system_id)');
        $this->addSql('CREATE INDEX IDX_CAE5E19F957BB53C ON visitor (langage_id)');
        $this->addSql('CREATE INDEX IDX_CAE5E19F56A273CC ON visitor (origin_id)');
        $this->addSql('CREATE INDEX IDX_CAE5E19F4C9BF8BD ON visitor (browser_id)');
        $this->addSql('CREATE INDEX IDX_CAE5E19FF92F3E70 ON visitor (country_id)');
        $this->addSql('CREATE INDEX IDX_CAE5E19FCCF9E01E ON visitor (departement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE area');
        $this->addSql('DROP TABLE browser');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE departement');
        $this->addSql('DROP TABLE langage');
        $this->addSql('DROP TABLE origin');
        $this->addSql('DROP TABLE page');
        $this->addSql('DROP TABLE request');
        $this->addSql('DROP TABLE site');
        $this->addSql('DROP TABLE site_status');
        $this->addSql('DROP TABLE system');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE visitor');
    }
}

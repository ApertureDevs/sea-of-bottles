<?php

declare(strict_types=1);

namespace RelationalModelMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version010000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE bottle (id VARCHAR(36) NOT NULL, receiver_id VARCHAR(36) DEFAULT NULL, message VARCHAR(5000) NOT NULL, create_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, receive_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_ACA9A955CD53EDB6 ON bottle (receiver_id)');
        $this->addSql('COMMENT ON COLUMN bottle.create_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN bottle.receive_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE sailor (id VARCHAR(36) NOT NULL, email VARCHAR(50) NOT NULL, create_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN sailor.create_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE bottle ADD CONSTRAINT FK_ACA9A955CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES sailor (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE bottle DROP CONSTRAINT FK_ACA9A955CD53EDB6');
        $this->addSql('DROP TABLE bottle');
        $this->addSql('DROP TABLE sailor');
    }
}

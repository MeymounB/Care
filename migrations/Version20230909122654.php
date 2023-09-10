<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230909122654 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE request DROP CONSTRAINT fk_3b978f9ff5b7af75');
        $this->addSql('DROP INDEX idx_3b978f9ff5b7af75');
        $this->addSql('ALTER TABLE request ADD address VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE request DROP address_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE request ADD address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE request DROP address');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT fk_3b978f9ff5b7af75 FOREIGN KEY (address_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_3b978f9ff5b7af75 ON request (address_id)');
    }
}

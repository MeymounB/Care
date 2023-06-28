<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230628115132 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment DROP CONSTRAINT fk_fe38f844bf396750');
        $this->addSql('ALTER TABLE advice DROP CONSTRAINT fk_64820e8dbf396750');
        $this->addSql('DROP TABLE appointment');
        $this->addSql('DROP TABLE advice');
        $this->addSql('ALTER TABLE request ADD planned_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE request ADD is_presential BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE request ADD adress VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE request ADD link VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE request ADD is_public BOOLEAN DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN request.planned_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE appointment (id INT NOT NULL, planned_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_presential BOOLEAN NOT NULL, adress VARCHAR(255) DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN appointment.planned_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE advice (id INT NOT NULL, is_public BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT fk_fe38f844bf396750 FOREIGN KEY (id) REFERENCES request (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE advice ADD CONSTRAINT fk_64820e8dbf396750 FOREIGN KEY (id) REFERENCES request (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request DROP planned_at');
        $this->addSql('ALTER TABLE request DROP is_presential');
        $this->addSql('ALTER TABLE request DROP adress');
        $this->addSql('ALTER TABLE request DROP link');
        $this->addSql('ALTER TABLE request DROP is_public');
    }
}

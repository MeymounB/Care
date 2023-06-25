<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230625111727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE request_plant (request_id INT NOT NULL, plant_id INT NOT NULL, PRIMARY KEY(request_id, plant_id))');
        $this->addSql('CREATE INDEX IDX_F614B157427EB8A5 ON request_plant (request_id)');
        $this->addSql('CREATE INDEX IDX_F614B1571D935652 ON request_plant (plant_id)');
        $this->addSql('ALTER TABLE request_plant ADD CONSTRAINT FK_F614B157427EB8A5 FOREIGN KEY (request_id) REFERENCES request (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request_plant ADD CONSTRAINT FK_F614B1571D935652 FOREIGN KEY (plant_id) REFERENCES plant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request DROP CONSTRAINT fk_3b978f9f1d935652');
        $this->addSql('DROP INDEX idx_3b978f9f1d935652');
        $this->addSql('ALTER TABLE request DROP plant_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE request_plant DROP CONSTRAINT FK_F614B157427EB8A5');
        $this->addSql('ALTER TABLE request_plant DROP CONSTRAINT FK_F614B1571D935652');
        $this->addSql('DROP TABLE request_plant');
        $this->addSql('ALTER TABLE request ADD plant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT fk_3b978f9f1d935652 FOREIGN KEY (plant_id) REFERENCES plant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_3b978f9f1d935652 ON request (plant_id)');
    }
}

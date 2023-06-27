<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230627083750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment ALTER COLUMN planned_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE USING planned_at::TIMESTAMP');
        $this->addSql('COMMENT ON COLUMN appointment.planned_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE request ALTER created_at TYPE DATE');
        $this->addSql('ALTER TABLE request ALTER updated_at TYPE DATE');
        $this->addSql('COMMENT ON COLUMN request.created_at IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN request.updated_at IS \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE "user" ADD is_accepted BOOLEAN DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE appointment ALTER COLUMN planned_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE USING planned_at::TIMESTAMP');
        $this->addSql('COMMENT ON COLUMN appointment.planned_at IS NULL');
        $this->addSql('ALTER TABLE request ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE request ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN request.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN request.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE "user" DROP is_accepted');
    }
}

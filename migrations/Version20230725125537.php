<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230725125537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address ALTER updated_at DROP NOT NULL');
        $this->addSql('ALTER TABLE certificate ALTER updated_at DROP NOT NULL');
        $this->addSql('ALTER TABLE request ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN request.updated_at IS NULL');
        $this->addSql('ALTER TABLE "user" ALTER updated_at DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE address ALTER updated_at SET NOT NULL');
        $this->addSql('ALTER TABLE request ALTER updated_at TYPE DATE');
        $this->addSql('COMMENT ON COLUMN request.updated_at IS \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE certificate ALTER updated_at SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER updated_at SET NOT NULL');
    }
}

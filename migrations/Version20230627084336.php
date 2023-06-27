<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230627084336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plant ALTER description DROP NOT NULL');
        $this->addSql('ALTER TABLE plant ALTER species DROP NOT NULL');
        $this->addSql('ALTER TABLE plant ALTER purchased_at DROP NOT NULL');
        $this->addSql('ALTER TABLE plant ALTER created_at DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE plant ALTER description SET NOT NULL');
        $this->addSql('ALTER TABLE plant ALTER species SET NOT NULL');
        $this->addSql('ALTER TABLE plant ALTER purchased_at SET NOT NULL');
        $this->addSql('ALTER TABLE plant ALTER created_at SET NOT NULL');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230616093906Pleaseerwanworkproperly extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844CC3D5E73 FOREIGN KEY (particular_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844C5802BC8 FOREIGN KEY (botanist_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE certificate ADD CONSTRAINT FK_219CDA4AC5802BC8 FOREIGN KEY (botanist_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE plant ADD CONSTRAINT FK_AB030D72CC3D5E73 FOREIGN KEY (particular_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DCC3D5E73 FOREIGN KEY (particular_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844CC3D5E73');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844C5802BC8');
        $this->addSql('ALTER TABLE certificate DROP FOREIGN KEY FK_219CDA4AC5802BC8');
        $this->addSql('ALTER TABLE plant DROP FOREIGN KEY FK_AB030D72CC3D5E73');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DCC3D5E73');
    }
}

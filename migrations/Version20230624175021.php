<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230624175021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4B89032C');
        $this->addSql('CREATE TABLE advice (id INT NOT NULL, is_public TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE request (id INT AUTO_INCREMENT NOT NULL, plant_id INT DEFAULT NULL, particular_id INT DEFAULT NULL, botanist_id INT DEFAULT NULL, status_id INT DEFAULT NULL, title LONGTEXT NOT NULL, description LONGTEXT DEFAULT NULL, date DATETIME DEFAULT NULL, type LONGTEXT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', dtype VARCHAR(255) NOT NULL, INDEX IDX_3B978F9F1D935652 (plant_id), INDEX IDX_3B978F9FCC3D5E73 (particular_id), INDEX IDX_3B978F9FC5802BC8 (botanist_id), INDEX IDX_3B978F9F6BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE advice ADD CONSTRAINT FK_64820E8DBF396750 FOREIGN KEY (id) REFERENCES request (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9F1D935652 FOREIGN KEY (plant_id) REFERENCES plant (id)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9FCC3D5E73 FOREIGN KEY (particular_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9FC5802BC8 FOREIGN KEY (botanist_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9F6BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE appointment_plant DROP FOREIGN KEY FK_47733C9C1D935652');
        $this->addSql('ALTER TABLE appointment_plant DROP FOREIGN KEY FK_47733C9CE5B533F9');
        $this->addSql('ALTER TABLE plant_post DROP FOREIGN KEY FK_3962AC9F4B89032C');
        $this->addSql('ALTER TABLE plant_post DROP FOREIGN KEY FK_3962AC9F1D935652');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DCC3D5E73');
        $this->addSql('DROP TABLE appointment_plant');
        $this->addSql('DROP TABLE plant_post');
        $this->addSql('DROP TABLE post');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844CC3D5E73');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844C5802BC8');
        $this->addSql('DROP INDEX IDX_FE38F844C5802BC8 ON appointment');
        $this->addSql('DROP INDEX IDX_FE38F844CC3D5E73 ON appointment');
        $this->addSql('ALTER TABLE appointment ADD is_presential TINYINT(1) NOT NULL, ADD adress VARCHAR(255) DEFAULT NULL, DROP particular_id, DROP botanist_id, DROP title, DROP type, DROP created_at, CHANGE id id INT NOT NULL, CHANGE planned_at planned_at VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844BF396750 FOREIGN KEY (id) REFERENCES request (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX IDX_9474526C4B89032C ON comment');
        $this->addSql('ALTER TABLE comment CHANGE post_id comment_plant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CD73554 FOREIGN KEY (comment_plant_id) REFERENCES plant (id)');
        $this->addSql('CREATE INDEX IDX_9474526CD73554 ON comment (comment_plant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844BF396750');
        $this->addSql('CREATE TABLE appointment_plant (appointment_id INT NOT NULL, plant_id INT NOT NULL, INDEX IDX_47733C9C1D935652 (plant_id), INDEX IDX_47733C9CE5B533F9 (appointment_id), PRIMARY KEY(appointment_id, plant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE plant_post (plant_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_3962AC9F4B89032C (post_id), INDEX IDX_3962AC9F1D935652 (plant_id), PRIMARY KEY(plant_id, post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, particular_id INT DEFAULT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, content LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_5A8A6C8DCC3D5E73 (particular_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE appointment_plant ADD CONSTRAINT FK_47733C9C1D935652 FOREIGN KEY (plant_id) REFERENCES plant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE appointment_plant ADD CONSTRAINT FK_47733C9CE5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plant_post ADD CONSTRAINT FK_3962AC9F4B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plant_post ADD CONSTRAINT FK_3962AC9F1D935652 FOREIGN KEY (plant_id) REFERENCES plant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DCC3D5E73 FOREIGN KEY (particular_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE advice DROP FOREIGN KEY FK_64820E8DBF396750');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9F1D935652');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9FCC3D5E73');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9FC5802BC8');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9F6BF700BD');
        $this->addSql('DROP TABLE advice');
        $this->addSql('DROP TABLE request');
        $this->addSql('DROP TABLE status');
        $this->addSql('ALTER TABLE appointment ADD particular_id INT DEFAULT NULL, ADD botanist_id INT DEFAULT NULL, ADD title VARCHAR(255) NOT NULL, ADD type VARCHAR(255) NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP is_presential, DROP adress, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE planned_at planned_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844CC3D5E73 FOREIGN KEY (particular_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844C5802BC8 FOREIGN KEY (botanist_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FE38F844C5802BC8 ON appointment (botanist_id)');
        $this->addSql('CREATE INDEX IDX_FE38F844CC3D5E73 ON appointment (particular_id)');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CD73554');
        $this->addSql('DROP INDEX IDX_9474526CD73554 ON comment');
        $this->addSql('ALTER TABLE comment CHANGE comment_plant_id post_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('CREATE INDEX IDX_9474526C4B89032C ON comment (post_id)');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230626072757 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, particular_id INT NOT NULL, street VARCHAR(255) NOT NULL, zip_code BIGINT NOT NULL, city VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, INDEX IDX_D4E6F81CC3D5E73 (particular_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE advice (id INT NOT NULL, is_public TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE appointment (id INT NOT NULL, planned_at VARCHAR(255) NOT NULL, is_presential TINYINT(1) NOT NULL, adress VARCHAR(255) DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE certificate (id INT AUTO_INCREMENT NOT NULL, botanist_id INT DEFAULT NULL, state VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, INDEX IDX_219CDA4AC5802BC8 (botanist_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, comment_plant_id INT DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9474526CA76ED395 (user_id), INDEX IDX_9474526CD73554 (comment_plant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, plant_id INT DEFAULT NULL, photo LONGBLOB NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_14B784181D935652 (plant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plant (id INT AUTO_INCREMENT NOT NULL, particular_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, species VARCHAR(255) NOT NULL, purchased_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', small_thumbnail VARCHAR(255) DEFAULT NULL, thumbnail VARCHAR(255) DEFAULT NULL, INDEX IDX_AB030D72CC3D5E73 (particular_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE request (id INT AUTO_INCREMENT NOT NULL, particular_id INT DEFAULT NULL, botanist_id INT DEFAULT NULL, status_id INT DEFAULT NULL, title LONGTEXT NOT NULL, description LONGTEXT DEFAULT NULL, date DATETIME DEFAULT NULL, type LONGTEXT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', dtype VARCHAR(255) NOT NULL, INDEX IDX_3B978F9FCC3D5E73 (particular_id), INDEX IDX_3B978F9FC5802BC8 (botanist_id), INDEX IDX_3B978F9F6BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE request_plant (request_id INT NOT NULL, plant_id INT NOT NULL, INDEX IDX_F614B157427EB8A5 (request_id), INDEX IDX_F614B1571D935652 (plant_id), PRIMARY KEY(request_id, plant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, name LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, cellphone BIGINT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, discr VARCHAR(255) NOT NULL, is_accepted TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81CC3D5E73 FOREIGN KEY (particular_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE advice ADD CONSTRAINT FK_64820E8DBF396750 FOREIGN KEY (id) REFERENCES request (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844BF396750 FOREIGN KEY (id) REFERENCES request (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE certificate ADD CONSTRAINT FK_219CDA4AC5802BC8 FOREIGN KEY (botanist_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CD73554 FOREIGN KEY (comment_plant_id) REFERENCES plant (id)');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B784181D935652 FOREIGN KEY (plant_id) REFERENCES plant (id)');
        $this->addSql('ALTER TABLE plant ADD CONSTRAINT FK_AB030D72CC3D5E73 FOREIGN KEY (particular_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9FCC3D5E73 FOREIGN KEY (particular_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9FC5802BC8 FOREIGN KEY (botanist_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9F6BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE request_plant ADD CONSTRAINT FK_F614B157427EB8A5 FOREIGN KEY (request_id) REFERENCES request (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE request_plant ADD CONSTRAINT FK_F614B1571D935652 FOREIGN KEY (plant_id) REFERENCES plant (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81CC3D5E73');
        $this->addSql('ALTER TABLE advice DROP FOREIGN KEY FK_64820E8DBF396750');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844BF396750');
        $this->addSql('ALTER TABLE certificate DROP FOREIGN KEY FK_219CDA4AC5802BC8');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CD73554');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B784181D935652');
        $this->addSql('ALTER TABLE plant DROP FOREIGN KEY FK_AB030D72CC3D5E73');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9FCC3D5E73');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9FC5802BC8');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9F6BF700BD');
        $this->addSql('ALTER TABLE request_plant DROP FOREIGN KEY FK_F614B157427EB8A5');
        $this->addSql('ALTER TABLE request_plant DROP FOREIGN KEY FK_F614B1571D935652');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE advice');
        $this->addSql('DROP TABLE appointment');
        $this->addSql('DROP TABLE certificate');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE plant');
        $this->addSql('DROP TABLE request');
        $this->addSql('DROP TABLE request_plant');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

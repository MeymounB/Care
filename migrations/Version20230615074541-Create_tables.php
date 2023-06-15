<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230615074541 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, street VARCHAR(255) NOT NULL, zip_code BIGINT NOT NULL, city VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE appointment (id INT AUTO_INCREMENT NOT NULL, particular_id INT DEFAULT NULL, botanist_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, planned_at DATETIME NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', link VARCHAR(255) DEFAULT NULL, INDEX IDX_FE38F844CC3D5E73 (particular_id), INDEX IDX_FE38F844C5802BC8 (botanist_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE appointment_plant (appointment_id INT NOT NULL, plant_id INT NOT NULL, INDEX IDX_47733C9CE5B533F9 (appointment_id), INDEX IDX_47733C9C1D935652 (plant_id), PRIMARY KEY(appointment_id, plant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE botanist (id INT NOT NULL, is_verified TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE certificate (id INT AUTO_INCREMENT NOT NULL, botanist_id INT DEFAULT NULL, state VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, INDEX IDX_219CDA4AC5802BC8 (botanist_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, post_id INT DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9474526CA76ED395 (user_id), INDEX IDX_9474526C4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE particular (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, plant_id INT DEFAULT NULL, photo LONGBLOB NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_14B784181D935652 (plant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plant (id INT AUTO_INCREMENT NOT NULL, particular_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, species VARCHAR(255) NOT NULL, purchased_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_AB030D72CC3D5E73 (particular_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plant_post (plant_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_3962AC9F1D935652 (plant_id), INDEX IDX_3962AC9F4B89032C (post_id), PRIMARY KEY(plant_id, post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, particular_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_5A8A6C8DCC3D5E73 (particular_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, cellphone BIGINT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, discr VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844CC3D5E73 FOREIGN KEY (particular_id) REFERENCES particular (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844C5802BC8 FOREIGN KEY (botanist_id) REFERENCES botanist (id)');
        $this->addSql('ALTER TABLE appointment_plant ADD CONSTRAINT FK_47733C9CE5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE appointment_plant ADD CONSTRAINT FK_47733C9C1D935652 FOREIGN KEY (plant_id) REFERENCES plant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE botanist ADD CONSTRAINT FK_7176BA06BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE certificate ADD CONSTRAINT FK_219CDA4AC5802BC8 FOREIGN KEY (botanist_id) REFERENCES botanist (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE particular ADD CONSTRAINT FK_862161CFBF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B784181D935652 FOREIGN KEY (plant_id) REFERENCES plant (id)');
        $this->addSql('ALTER TABLE plant ADD CONSTRAINT FK_AB030D72CC3D5E73 FOREIGN KEY (particular_id) REFERENCES particular (id)');
        $this->addSql('ALTER TABLE plant_post ADD CONSTRAINT FK_3962AC9F1D935652 FOREIGN KEY (plant_id) REFERENCES plant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plant_post ADD CONSTRAINT FK_3962AC9F4B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DCC3D5E73 FOREIGN KEY (particular_id) REFERENCES particular (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844CC3D5E73');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844C5802BC8');
        $this->addSql('ALTER TABLE appointment_plant DROP FOREIGN KEY FK_47733C9CE5B533F9');
        $this->addSql('ALTER TABLE appointment_plant DROP FOREIGN KEY FK_47733C9C1D935652');
        $this->addSql('ALTER TABLE botanist DROP FOREIGN KEY FK_7176BA06BF396750');
        $this->addSql('ALTER TABLE certificate DROP FOREIGN KEY FK_219CDA4AC5802BC8');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4B89032C');
        $this->addSql('ALTER TABLE particular DROP FOREIGN KEY FK_862161CFBF396750');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B784181D935652');
        $this->addSql('ALTER TABLE plant DROP FOREIGN KEY FK_AB030D72CC3D5E73');
        $this->addSql('ALTER TABLE plant_post DROP FOREIGN KEY FK_3962AC9F1D935652');
        $this->addSql('ALTER TABLE plant_post DROP FOREIGN KEY FK_3962AC9F4B89032C');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DCC3D5E73');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE appointment');
        $this->addSql('DROP TABLE appointment_plant');
        $this->addSql('DROP TABLE botanist');
        $this->addSql('DROP TABLE certificate');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE particular');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE plant');
        $this->addSql('DROP TABLE plant_post');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

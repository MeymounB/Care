<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230625194313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE address_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE certificate_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE photo_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE plant_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE status_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE address (id INT NOT NULL, particular_id INT NOT NULL, street VARCHAR(255) NOT NULL, zip_code BIGINT NOT NULL, city VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D4E6F81CC3D5E73 ON address (particular_id)');
        $this->addSql('COMMENT ON COLUMN address.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE advice (id INT NOT NULL, is_public BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE appointment (id INT NOT NULL, planned_at VARCHAR(255) NOT NULL, is_presential BOOLEAN NOT NULL, adress VARCHAR(255) DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE certificate (id INT NOT NULL, botanist_id INT DEFAULT NULL, state VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_219CDA4AC5802BC8 ON certificate (botanist_id)');
        $this->addSql('COMMENT ON COLUMN certificate.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE comment (id INT NOT NULL, user_id INT DEFAULT NULL, comment_plant_id INT DEFAULT NULL, content TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9474526CA76ED395 ON comment (user_id)');
        $this->addSql('CREATE INDEX IDX_9474526CD73554 ON comment (comment_plant_id)');
        $this->addSql('COMMENT ON COLUMN comment.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE photo (id INT NOT NULL, plant_id INT DEFAULT NULL, photo BYTEA NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_14B784181D935652 ON photo (plant_id)');
        $this->addSql('COMMENT ON COLUMN photo.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE plant (id INT NOT NULL, particular_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, species VARCHAR(255) NOT NULL, purchased_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, small_thumbnail VARCHAR(255) DEFAULT NULL, thumbnail VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AB030D72CC3D5E73 ON plant (particular_id)');
        $this->addSql('COMMENT ON COLUMN plant.purchased_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN plant.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE request (id INT NOT NULL, particular_id INT DEFAULT NULL, botanist_id INT DEFAULT NULL, status_id INT DEFAULT NULL, title TEXT NOT NULL, description TEXT DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, type TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, dtype VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3B978F9FCC3D5E73 ON request (particular_id)');
        $this->addSql('CREATE INDEX IDX_3B978F9FC5802BC8 ON request (botanist_id)');
        $this->addSql('CREATE INDEX IDX_3B978F9F6BF700BD ON request (status_id)');
        $this->addSql('COMMENT ON COLUMN request.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN request.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE request_plant (request_id INT NOT NULL, plant_id INT NOT NULL, PRIMARY KEY(request_id, plant_id))');
        $this->addSql('CREATE INDEX IDX_F614B157427EB8A5 ON request_plant (request_id)');
        $this->addSql('CREATE INDEX IDX_F614B1571D935652 ON request_plant (plant_id)');
        $this->addSql('CREATE TABLE status (id INT NOT NULL, name TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, cellphone BIGINT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL, discr VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81CC3D5E73 FOREIGN KEY (particular_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE advice ADD CONSTRAINT FK_64820E8DBF396750 FOREIGN KEY (id) REFERENCES request (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844BF396750 FOREIGN KEY (id) REFERENCES request (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE certificate ADD CONSTRAINT FK_219CDA4AC5802BC8 FOREIGN KEY (botanist_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CD73554 FOREIGN KEY (comment_plant_id) REFERENCES plant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B784181D935652 FOREIGN KEY (plant_id) REFERENCES plant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE plant ADD CONSTRAINT FK_AB030D72CC3D5E73 FOREIGN KEY (particular_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9FCC3D5E73 FOREIGN KEY (particular_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9FC5802BC8 FOREIGN KEY (botanist_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9F6BF700BD FOREIGN KEY (status_id) REFERENCES status (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request_plant ADD CONSTRAINT FK_F614B157427EB8A5 FOREIGN KEY (request_id) REFERENCES request (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request_plant ADD CONSTRAINT FK_F614B1571D935652 FOREIGN KEY (plant_id) REFERENCES plant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE address_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE certificate_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE comment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE photo_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE plant_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE request_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE status_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE address DROP CONSTRAINT FK_D4E6F81CC3D5E73');
        $this->addSql('ALTER TABLE advice DROP CONSTRAINT FK_64820E8DBF396750');
        $this->addSql('ALTER TABLE appointment DROP CONSTRAINT FK_FE38F844BF396750');
        $this->addSql('ALTER TABLE certificate DROP CONSTRAINT FK_219CDA4AC5802BC8');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CD73554');
        $this->addSql('ALTER TABLE photo DROP CONSTRAINT FK_14B784181D935652');
        $this->addSql('ALTER TABLE plant DROP CONSTRAINT FK_AB030D72CC3D5E73');
        $this->addSql('ALTER TABLE request DROP CONSTRAINT FK_3B978F9FCC3D5E73');
        $this->addSql('ALTER TABLE request DROP CONSTRAINT FK_3B978F9FC5802BC8');
        $this->addSql('ALTER TABLE request DROP CONSTRAINT FK_3B978F9F6BF700BD');
        $this->addSql('ALTER TABLE request_plant DROP CONSTRAINT FK_F614B157427EB8A5');
        $this->addSql('ALTER TABLE request_plant DROP CONSTRAINT FK_F614B1571D935652');
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
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

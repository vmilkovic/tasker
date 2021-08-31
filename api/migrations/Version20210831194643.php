<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210831194643 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE attachment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE timer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE attachment (id INT NOT NULL, task_id INT DEFAULT NULL, comment_id INT DEFAULT NULL, uuid UUID NOT NULL, name VARCHAR(255) NOT NULL, path VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_795FD9BB8DB60186 ON attachment (task_id)');
        $this->addSql('CREATE INDEX IDX_795FD9BBF8697D13 ON attachment (comment_id)');
        $this->addSql('COMMENT ON COLUMN attachment.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN attachment.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN attachment.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN attachment.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE comment (id INT NOT NULL, commenter_id INT DEFAULT NULL, task_id INT DEFAULT NULL, uuid UUID NOT NULL, text TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9474526CB4D5A9E2 ON comment (commenter_id)');
        $this->addSql('CREATE INDEX IDX_9474526C8DB60186 ON comment (task_id)');
        $this->addSql('COMMENT ON COLUMN comment.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN comment.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN comment.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN comment.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE timer (id INT NOT NULL, comment_id INT DEFAULT NULL, uuid UUID NOT NULL, track_from TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, track_to TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6AD0DE1AF8697D13 ON timer (comment_id)');
        $this->addSql('COMMENT ON COLUMN timer.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN timer.track_from IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN timer.track_to IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN timer.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN timer.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN timer.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE attachment ADD CONSTRAINT FK_795FD9BB8DB60186 FOREIGN KEY (task_id) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attachment ADD CONSTRAINT FK_795FD9BBF8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CB4D5A9E2 FOREIGN KEY (commenter_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C8DB60186 FOREIGN KEY (task_id) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE timer ADD CONSTRAINT FK_6AD0DE1AF8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task ADD timer_id INT NOT NULL');
        $this->addSql('ALTER TABLE task ADD position SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25EE98D9B9 FOREIGN KEY (timer_id) REFERENCES timer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_527EDB25EE98D9B9 ON task (timer_id)');
        $this->addSql('ALTER TABLE workflow ALTER "position" TYPE SMALLINT');
        $this->addSql('ALTER TABLE workflow ALTER "position" DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE attachment DROP CONSTRAINT FK_795FD9BBF8697D13');
        $this->addSql('ALTER TABLE timer DROP CONSTRAINT FK_6AD0DE1AF8697D13');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB25EE98D9B9');
        $this->addSql('DROP SEQUENCE attachment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE comment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE timer_id_seq CASCADE');
        $this->addSql('DROP TABLE attachment');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE timer');
        $this->addSql('ALTER TABLE workflow ALTER position TYPE INT');
        $this->addSql('ALTER TABLE workflow ALTER position DROP DEFAULT');
        $this->addSql('DROP INDEX UNIQ_527EDB25EE98D9B9');
        $this->addSql('ALTER TABLE task DROP timer_id');
        $this->addSql('ALTER TABLE task DROP position');
    }
}

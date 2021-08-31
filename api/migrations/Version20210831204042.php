<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210831204042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE issue_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE issue (id INT NOT NULL, timer_id INT NOT NULL, created_by_id INT NOT NULL, assigned_to_id INT DEFAULT NULL, task_id INT NOT NULL, uuid UUID NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, is_resolved BOOLEAN NOT NULL, estimate_from TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, estimate_to TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_12AD233EEE98D9B9 ON issue (timer_id)');
        $this->addSql('CREATE INDEX IDX_12AD233EB03A8386 ON issue (created_by_id)');
        $this->addSql('CREATE INDEX IDX_12AD233EF4BD7827 ON issue (assigned_to_id)');
        $this->addSql('CREATE INDEX IDX_12AD233E8DB60186 ON issue (task_id)');
        $this->addSql('COMMENT ON COLUMN issue.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN issue.estimate_from IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN issue.estimate_to IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN issue.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN issue.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN issue.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE issue ADD CONSTRAINT FK_12AD233EEE98D9B9 FOREIGN KEY (timer_id) REFERENCES timer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE issue ADD CONSTRAINT FK_12AD233EB03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE issue ADD CONSTRAINT FK_12AD233EF4BD7827 FOREIGN KEY (assigned_to_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE issue ADD CONSTRAINT FK_12AD233E8DB60186 FOREIGN KEY (task_id) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attachment ADD issue_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE attachment ADD CONSTRAINT FK_795FD9BB5E7AA58C FOREIGN KEY (issue_id) REFERENCES issue (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_795FD9BB5E7AA58C ON attachment (issue_id)');
        $this->addSql('ALTER TABLE comment ADD issue_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C5E7AA58C FOREIGN KEY (issue_id) REFERENCES issue (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9474526C5E7AA58C ON comment (issue_id)');
        $this->addSql('ALTER TABLE task ADD created_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_527EDB25B03A8386 ON task (created_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE attachment DROP CONSTRAINT FK_795FD9BB5E7AA58C');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C5E7AA58C');
        $this->addSql('DROP SEQUENCE issue_id_seq CASCADE');
        $this->addSql('DROP TABLE issue');
        $this->addSql('DROP INDEX IDX_795FD9BB5E7AA58C');
        $this->addSql('ALTER TABLE attachment DROP issue_id');
        $this->addSql('DROP INDEX IDX_9474526C5E7AA58C');
        $this->addSql('ALTER TABLE comment DROP issue_id');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB25B03A8386');
        $this->addSql('DROP INDEX IDX_527EDB25B03A8386');
        $this->addSql('ALTER TABLE task DROP created_by_id');
    }
}

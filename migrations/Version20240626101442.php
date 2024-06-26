<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240626101442 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_person (project_id INT NOT NULL, person_id INT NOT NULL, PRIMARY KEY(project_id, person_id))');
        $this->addSql('CREATE INDEX IDX_23783A3C166D1F9C ON project_person (project_id)');
        $this->addSql('CREATE INDEX IDX_23783A3C217BBB47 ON project_person (person_id)');
        $this->addSql('ALTER TABLE project_person ADD CONSTRAINT FK_23783A3C166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_person ADD CONSTRAINT FK_23783A3C217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE assignment ADD person_id INT NOT NULL');
        $this->addSql('ALTER TABLE assignment ADD CONSTRAINT FK_30C544BA217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_30C544BA217BBB47 ON assignment (person_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE project_person DROP CONSTRAINT FK_23783A3C166D1F9C');
        $this->addSql('ALTER TABLE project_person DROP CONSTRAINT FK_23783A3C217BBB47');
        $this->addSql('DROP TABLE project_person');
        $this->addSql('ALTER TABLE assignment DROP CONSTRAINT FK_30C544BA217BBB47');
        $this->addSql('DROP INDEX IDX_30C544BA217BBB47');
        $this->addSql('ALTER TABLE assignment DROP person_id');
    }
}

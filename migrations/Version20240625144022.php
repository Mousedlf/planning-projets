<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240625144022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE assignment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE profile_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE assignment (id INT NOT NULL, profile_id INT NOT NULL, project_id INT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_30C544BACCFA12B8 ON assignment (profile_id)');
        $this->addSql('CREATE INDEX IDX_30C544BA166D1F9C ON assignment (project_id)');
        $this->addSql('CREATE TABLE profile (id INT NOT NULL, of_user_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8157AA0F5A1B2224 ON profile (of_user_id)');
        $this->addSql('ALTER TABLE assignment ADD CONSTRAINT FK_30C544BACCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE assignment ADD CONSTRAINT FK_30C544BA166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE profile ADD CONSTRAINT FK_8157AA0F5A1B2224 FOREIGN KEY (of_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE assignment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE profile_id_seq CASCADE');
        $this->addSql('ALTER TABLE assignment DROP CONSTRAINT FK_30C544BACCFA12B8');
        $this->addSql('ALTER TABLE assignment DROP CONSTRAINT FK_30C544BA166D1F9C');
        $this->addSql('ALTER TABLE profile DROP CONSTRAINT FK_8157AA0F5A1B2224');
        $this->addSql('DROP TABLE assignment');
        $this->addSql('DROP TABLE profile');
    }
}

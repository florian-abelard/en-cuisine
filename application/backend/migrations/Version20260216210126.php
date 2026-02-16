<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260216210126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE realisation (id SERIAL NOT NULL, recette_id INT NOT NULL, date DATE NOT NULL, notes TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EAA5610E89312FE9 ON realisation (recette_id)');
        $this->addSql('CREATE TABLE realisation_media (realisation_id INT NOT NULL, media_id INT NOT NULL, PRIMARY KEY(realisation_id, media_id))');
        $this->addSql('CREATE INDEX IDX_A08CCE5CB685E551 ON realisation_media (realisation_id)');
        $this->addSql('CREATE INDEX IDX_A08CCE5CEA9FDD75 ON realisation_media (media_id)');
        $this->addSql('ALTER TABLE realisation ADD CONSTRAINT FK_EAA5610E89312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE realisation_media ADD CONSTRAINT FK_A08CCE5CB685E551 FOREIGN KEY (realisation_id) REFERENCES realisation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE realisation_media ADD CONSTRAINT FK_A08CCE5CEA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE realisation DROP CONSTRAINT FK_EAA5610E89312FE9');
        $this->addSql('ALTER TABLE realisation_media DROP CONSTRAINT FK_A08CCE5CB685E551');
        $this->addSql('ALTER TABLE realisation_media DROP CONSTRAINT FK_A08CCE5CEA9FDD75');
        $this->addSql('DROP TABLE realisation');
        $this->addSql('DROP TABLE realisation_media');
    }
}

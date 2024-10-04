<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241003201737 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recette ADD pret_dans INTERVAL DEFAULT NULL');
        $this->addSql('ALTER TABLE recette ADD temps_de_preparation INTERVAL DEFAULT NULL');
        $this->addSql('ALTER TABLE recette ADD temps_de_cuisson INTERVAL DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE recette DROP pret_dans');
        $this->addSql('ALTER TABLE recette DROP temps_de_preparation');
        $this->addSql('ALTER TABLE recette DROP temps_de_cuisson');
    }
}

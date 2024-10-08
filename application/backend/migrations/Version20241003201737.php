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
        $this->addSql('CREATE TABLE etiquette (id SERIAL NOT NULL, libelle VARCHAR(255) NOT NULL, color VARCHAR(7) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE ingredient (id SERIAL NOT NULL, libelle VARCHAR(255) NOT NULL, "order" INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE recette_ingredient (recette_id INT NOT NULL, ingredient_id INT NOT NULL, PRIMARY KEY(recette_id, ingredient_id))');
        $this->addSql('CREATE INDEX IDX_17C041A989312FE9 ON recette_ingredient (recette_id)');
        $this->addSql('CREATE INDEX IDX_17C041A9933FE08C ON recette_ingredient (ingredient_id)');
        $this->addSql('CREATE TABLE recette_etiquette (recette_id INT NOT NULL, etiquette_id INT NOT NULL, PRIMARY KEY(recette_id, etiquette_id))');
        $this->addSql('CREATE INDEX IDX_ADD8779489312FE9 ON recette_etiquette (recette_id)');
        $this->addSql('CREATE INDEX IDX_ADD877947BD2EA57 ON recette_etiquette (etiquette_id)');
        $this->addSql('ALTER TABLE recette_ingredient ADD CONSTRAINT FK_17C041A989312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recette_ingredient ADD CONSTRAINT FK_17C041A9933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recette_etiquette ADD CONSTRAINT FK_ADD8779489312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recette_etiquette ADD CONSTRAINT FK_ADD877947BD2EA57 FOREIGN KEY (etiquette_id) REFERENCES etiquette (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recette ADD description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE recette ADD source VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE recette ADD note TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE recette ALTER pret_dans TYPE INTERVAL');
        $this->addSql('ALTER TABLE recette ALTER temps_de_preparation TYPE INTERVAL');
        $this->addSql('ALTER TABLE recette ALTER temps_de_cuisson TYPE INTERVAL');
        $this->addSql('COMMENT ON COLUMN recette.pret_dans IS \'(DC2Type:app_dateinterval)\'');
        $this->addSql('COMMENT ON COLUMN recette.temps_de_preparation IS \'(DC2Type:app_dateinterval)\'');
        $this->addSql('COMMENT ON COLUMN recette.temps_de_cuisson IS \'(DC2Type:app_dateinterval)\'');
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

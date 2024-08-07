<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Doctrine\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240806154052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addMultipleSql(
            <<<SQL
                insert into categorie(libelle, "order") values(:libelle, :order)
            SQL,
            [
                ['libelle' => 'Entrée', 'order' => 1],
                ['libelle' => 'Plat', 'order' => 2],
                ['libelle' => 'Dessert', 'order' => 3],
                ['libelle' => 'Boisson', 'order' => 4],
            ]
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}

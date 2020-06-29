<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200629105753 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, home_team_id_id INT NOT NULL, away_team_id_id INT NOT NULL, date DATETIME NOT NULL, city VARCHAR(255) NOT NULL, INDEX IDX_232B318CC98D86E7 (home_team_id_id), INDEX IDX_232B318C79BCAA54 (away_team_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CC98D86E7 FOREIGN KEY (home_team_id_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C79BCAA54 FOREIGN KEY (away_team_id_id) REFERENCES team (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE game');
    }
}

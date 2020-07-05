<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200705075858 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tip (id INT AUTO_INCREMENT NOT NULL, tip_ticket_id INT NOT NULL, game_id INT NOT NULL, home_team_score SMALLINT NOT NULL, away_team_score SMALLINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_4883B84CE0F6DA43 (tip_ticket_id), INDEX IDX_4883B84CE48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tip ADD CONSTRAINT FK_4883B84CE0F6DA43 FOREIGN KEY (tip_ticket_id) REFERENCES tip_ticket (id)');
        $this->addSql('ALTER TABLE tip ADD CONSTRAINT FK_4883B84CE48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tip');
    }
}

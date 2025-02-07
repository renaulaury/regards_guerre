<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250207133539 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exhibition ADD hook_exhibit LONGTEXT DEFAULT NULL, CHANGE main_image_alt main_image_alt VARCHAR(255) NOT NULL, CHANGE title_exhibit title_exhibit VARCHAR(255) NOT NULL, CHANGE date_war_begin date_war_begin DATE NOT NULL, CHANGE date_war_end date_war_end DATE NOT NULL');
        $this->addSql('ALTER TABLE user ADD roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exhibition DROP hook_exhibit, CHANGE title_exhibit title_exhibit VARCHAR(100) NOT NULL, CHANGE date_war_begin date_war_begin DATE DEFAULT NULL, CHANGE date_war_end date_war_end DATE DEFAULT NULL, CHANGE main_image_alt main_image_alt VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP roles');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250205123741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE artiste (id INT AUTO_INCREMENT NOT NULL, artist_name VARCHAR(50) NOT NULL, artist_firstname VARCHAR(50) NOT NULL, artist_birth_date DATE NOT NULL, artist_death_date DATE NOT NULL, artist_photo VARCHAR(255) NOT NULL, artist_job VARCHAR(50) NOT NULL, artist_bio LONGTEXT NOT NULL, artist_text_art LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, comment_text LONGTEXT NOT NULL, date_comment_creation DATETIME NOT NULL, INDEX IDX_9474526CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exhibition (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, title_exhibit VARCHAR(50) NOT NULL, main_image VARCHAR(255) DEFAULT NULL, date_war_begin DATE NOT NULL, date_war_end DATE NOT NULL, date_exhibit DATE NOT NULL, hour_begin TIME NOT NULL, hour_end TIME NOT NULL, description_exhibit LONGTEXT NOT NULL, INDEX IDX_B8353389A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, order_date_creation DATE NOT NULL, order_status VARCHAR(50) NOT NULL, INDEX IDX_F5299398A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_detail (id INT AUTO_INCREMENT NOT NULL, order__id INT DEFAULT NULL, exhibition_id INT DEFAULT NULL, ticket_id INT DEFAULT NULL, unit_price NUMERIC(10, 2) NOT NULL, quantity INT NOT NULL, INDEX IDX_ED896F46251A8A50 (order__id), INDEX IDX_ED896F462A7D4494 (exhibition_id), INDEX IDX_ED896F46700047D2 (ticket_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, title_room VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `show` (id INT AUTO_INCREMENT NOT NULL, room_id INT DEFAULT NULL, artiste_id INT DEFAULT NULL, exhibition_id INT DEFAULT NULL, INDEX IDX_320ED90154177093 (room_id), INDEX IDX_320ED90121D25844 (artiste_id), INDEX IDX_320ED9012A7D4494 (exhibition_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, title_ticket VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket_pricing (id INT AUTO_INCREMENT NOT NULL, ticket_id INT DEFAULT NULL, exhibition_id INT DEFAULT NULL, standard_price NUMERIC(10, 2) NOT NULL, INDEX IDX_E93DF561700047D2 (ticket_id), INDEX IDX_E93DF5612A7D4494 (exhibition_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, user_email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, user_nickname VARCHAR(50) NOT NULL, role VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USER_EMAIL (user_email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE exhibition ADD CONSTRAINT FK_B8353389A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F46251A8A50 FOREIGN KEY (order__id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F462A7D4494 FOREIGN KEY (exhibition_id) REFERENCES exhibition (id)');
        $this->addSql('ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F46700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id)');
        $this->addSql('ALTER TABLE `show` ADD CONSTRAINT FK_320ED90154177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE `show` ADD CONSTRAINT FK_320ED90121D25844 FOREIGN KEY (artiste_id) REFERENCES artiste (id)');
        $this->addSql('ALTER TABLE `show` ADD CONSTRAINT FK_320ED9012A7D4494 FOREIGN KEY (exhibition_id) REFERENCES exhibition (id)');
        $this->addSql('ALTER TABLE ticket_pricing ADD CONSTRAINT FK_E93DF561700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id)');
        $this->addSql('ALTER TABLE ticket_pricing ADD CONSTRAINT FK_E93DF5612A7D4494 FOREIGN KEY (exhibition_id) REFERENCES exhibition (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE exhibition DROP FOREIGN KEY FK_B8353389A76ED395');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE order_detail DROP FOREIGN KEY FK_ED896F46251A8A50');
        $this->addSql('ALTER TABLE order_detail DROP FOREIGN KEY FK_ED896F462A7D4494');
        $this->addSql('ALTER TABLE order_detail DROP FOREIGN KEY FK_ED896F46700047D2');
        $this->addSql('ALTER TABLE `show` DROP FOREIGN KEY FK_320ED90154177093');
        $this->addSql('ALTER TABLE `show` DROP FOREIGN KEY FK_320ED90121D25844');
        $this->addSql('ALTER TABLE `show` DROP FOREIGN KEY FK_320ED9012A7D4494');
        $this->addSql('ALTER TABLE ticket_pricing DROP FOREIGN KEY FK_E93DF561700047D2');
        $this->addSql('ALTER TABLE ticket_pricing DROP FOREIGN KEY FK_E93DF5612A7D4494');
        $this->addSql('DROP TABLE artiste');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE exhibition');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_detail');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE `show`');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE ticket_pricing');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

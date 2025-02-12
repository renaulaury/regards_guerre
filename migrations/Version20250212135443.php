<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250212135443 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE artist (id INT AUTO_INCREMENT NOT NULL, artist_name VARCHAR(50) NOT NULL, artist_firstname VARCHAR(50) NOT NULL, artist_birth_date DATE NOT NULL, artist_death_date DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, exhibition_id INT DEFAULT NULL, comment_text LONGTEXT NOT NULL, date_comment_creation DATETIME NOT NULL, INDEX IDX_9474526CA76ED395 (user_id), INDEX IDX_9474526C2A7D4494 (exhibition_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exhibition (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, title_exhibit VARCHAR(255) NOT NULL, main_image VARCHAR(255) DEFAULT NULL, date_war_begin DATE NOT NULL, date_war_end DATE NOT NULL, date_exhibit DATE NOT NULL, hour_begin TIME NOT NULL, hour_end TIME NOT NULL, description_exhibit LONGTEXT NOT NULL, main_image_alt VARCHAR(255) NOT NULL, hook_exhibit LONGTEXT NOT NULL, subtitle_exhibit VARCHAR(255) NOT NULL, INDEX IDX_B8353389A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, order_date_creation DATE NOT NULL, order_status VARCHAR(50) NOT NULL, INDEX IDX_F5299398A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_detail (id INT AUTO_INCREMENT NOT NULL, order__id INT DEFAULT NULL, exhibition_id INT DEFAULT NULL, product_id INT NOT NULL, unit_price NUMERIC(10, 2) NOT NULL, quantity INT NOT NULL, INDEX IDX_ED896F46251A8A50 (order__id), INDEX IDX_ED896F462A7D4494 (exhibition_id), INDEX IDX_ED896F464584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, title_product VARCHAR(50) NOT NULL, image_product VARCHAR(255) NOT NULL, image_product_alt VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_pricing (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, exhibition_id INT NOT NULL, standard_price NUMERIC(15, 2) NOT NULL, INDEX IDX_3428B7834584665A (product_id), INDEX IDX_3428B7832A7D4494 (exhibition_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, title_room VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `show` (id INT AUTO_INCREMENT NOT NULL, room_id INT DEFAULT NULL, exhibition_id INT DEFAULT NULL, artist_id INT DEFAULT NULL, artist_photo VARCHAR(255) DEFAULT NULL, artist_job VARCHAR(50) NOT NULL, artist_bio LONGTEXT NOT NULL, artist_text_art LONGTEXT NOT NULL, artist_photo_alt VARCHAR(255) DEFAULT NULL, INDEX IDX_320ED90154177093 (room_id), INDEX IDX_320ED9012A7D4494 (exhibition_id), INDEX IDX_320ED901B7970CF8 (artist_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, title_type VARCHAR(50) NOT NULL, INDEX IDX_8CDE57294584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, user_email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, user_nickname VARCHAR(50) NOT NULL, role VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USER_EMAIL (user_email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C2A7D4494 FOREIGN KEY (exhibition_id) REFERENCES exhibition (id)');
        $this->addSql('ALTER TABLE exhibition ADD CONSTRAINT FK_B8353389A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F46251A8A50 FOREIGN KEY (order__id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F462A7D4494 FOREIGN KEY (exhibition_id) REFERENCES exhibition (id)');
        $this->addSql('ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F464584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_pricing ADD CONSTRAINT FK_3428B7834584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_pricing ADD CONSTRAINT FK_3428B7832A7D4494 FOREIGN KEY (exhibition_id) REFERENCES exhibition (id)');
        $this->addSql('ALTER TABLE `show` ADD CONSTRAINT FK_320ED90154177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE `show` ADD CONSTRAINT FK_320ED9012A7D4494 FOREIGN KEY (exhibition_id) REFERENCES exhibition (id)');
        $this->addSql('ALTER TABLE `show` ADD CONSTRAINT FK_320ED901B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id)');
        $this->addSql('ALTER TABLE type ADD CONSTRAINT FK_8CDE57294584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C2A7D4494');
        $this->addSql('ALTER TABLE exhibition DROP FOREIGN KEY FK_B8353389A76ED395');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE order_detail DROP FOREIGN KEY FK_ED896F46251A8A50');
        $this->addSql('ALTER TABLE order_detail DROP FOREIGN KEY FK_ED896F462A7D4494');
        $this->addSql('ALTER TABLE order_detail DROP FOREIGN KEY FK_ED896F464584665A');
        $this->addSql('ALTER TABLE product_pricing DROP FOREIGN KEY FK_3428B7834584665A');
        $this->addSql('ALTER TABLE product_pricing DROP FOREIGN KEY FK_3428B7832A7D4494');
        $this->addSql('ALTER TABLE `show` DROP FOREIGN KEY FK_320ED90154177093');
        $this->addSql('ALTER TABLE `show` DROP FOREIGN KEY FK_320ED9012A7D4494');
        $this->addSql('ALTER TABLE `show` DROP FOREIGN KEY FK_320ED901B7970CF8');
        $this->addSql('ALTER TABLE type DROP FOREIGN KEY FK_8CDE57294584665A');
        $this->addSql('DROP TABLE artist');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE exhibition');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_detail');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_pricing');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE `show`');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

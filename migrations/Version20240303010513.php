<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240303010513 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE booking_l (id INT AUTO_INCREMENT NOT NULL, prix DOUBLE PRECISION NOT NULL, date_d DATE NOT NULL, date_f DATE NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event DROP INDEX UNIQ_3BAE0AA76AB213CC, ADD INDEX IDX_3BAE0AA76AB213CC (lieu_id)');
        $this->addSql('ALTER TABLE lieu ADD date_f DATE NOT NULL, ADD capacity INT NOT NULL, ADD region VARCHAR(255) NOT NULL, CHANGE category_id category_id INT NOT NULL, CHANGE date date_d DATE NOT NULL');
        $this->addSql('ALTER TABLE user ADD enabled TINYINT(1) NOT NULL, ADD roles JSON NOT NULL COMMENT \'(DC2Type:json)\', CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE email email VARCHAR(180) NOT NULL, CHANGE fname fname VARCHAR(180) NOT NULL, CHANGE lname lname VARCHAR(180) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE booking_l');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('ALTER TABLE event DROP INDEX IDX_3BAE0AA76AB213CC, ADD UNIQUE INDEX UNIQ_3BAE0AA76AB213CC (lieu_id)');
        $this->addSql('ALTER TABLE lieu ADD date DATE NOT NULL, DROP date_d, DROP date_f, DROP capacity, DROP region, CHANGE category_id category_id INT DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('ALTER TABLE user DROP enabled, DROP roles, CHANGE id id INT NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE fname fname VARCHAR(255) NOT NULL, CHANGE lname lname VARCHAR(255) NOT NULL');
    }
}

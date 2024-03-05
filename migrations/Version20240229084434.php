<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240229084434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE booking_l (id INT AUTO_INCREMENT NOT NULL, prix DOUBLE PRECISION NOT NULL, date_d DATE NOT NULL, date_f DATE NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event DROP INDEX UNIQ_3BAE0AA76AB213CC, ADD INDEX IDX_3BAE0AA76AB213CC (lieu_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE booking_l');
        $this->addSql('ALTER TABLE event DROP INDEX IDX_3BAE0AA76AB213CC, ADD UNIQUE INDEX UNIQ_3BAE0AA76AB213CC (lieu_id)');
    }
}

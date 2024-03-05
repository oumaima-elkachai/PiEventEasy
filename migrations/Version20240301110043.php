<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240301110043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking_l ADD lieub_id INT NOT NULL');
        $this->addSql('ALTER TABLE booking_l ADD CONSTRAINT FK_F9FEE84F9D6CB157 FOREIGN KEY (lieub_id) REFERENCES lieu (id)');
        $this->addSql('CREATE INDEX IDX_F9FEE84F9D6CB157 ON booking_l (lieub_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking_l DROP FOREIGN KEY FK_F9FEE84F9D6CB157');
        $this->addSql('DROP INDEX IDX_F9FEE84F9D6CB157 ON booking_l');
        $this->addSql('ALTER TABLE booking_l DROP lieub_id');
    }
}

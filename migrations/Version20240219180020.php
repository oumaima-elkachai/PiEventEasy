<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240219180020 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lieu DROP FOREIGN KEY FK_2F577D5912469DE2');
        $this->addSql('DROP INDEX IDX_2F577D5912469DE2 ON lieu');
        $this->addSql('ALTER TABLE lieu ADD date_f DATE NOT NULL, ADD capacity INT NOT NULL, DROP category_id, CHANGE date date_d DATE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lieu ADD category_id INT DEFAULT NULL, ADD date DATE NOT NULL, DROP date_d, DROP date_f, DROP capacity');
        $this->addSql('ALTER TABLE lieu ADD CONSTRAINT FK_2F577D5912469DE2 FOREIGN KEY (category_id) REFERENCES category_l (id)');
        $this->addSql('CREATE INDEX IDX_2F577D5912469DE2 ON lieu (category_id)');
    }
}

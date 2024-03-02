<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240216203130 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE allocation ADD event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE allocation ADD CONSTRAINT FK_5C44232A71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('CREATE INDEX IDX_5C44232A71F7E88B ON allocation (event_id)');
        $this->addSql('ALTER TABLE event ADD lieu_id INT DEFAULT NULL, ADD userid_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA76AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA758E0A285 FOREIGN KEY (userid_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3BAE0AA76AB213CC ON event (lieu_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA758E0A285 ON event (userid_id)');
        $this->addSql('ALTER TABLE note ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CFBDFA14A76ED395 ON note (user_id)');
        $this->addSql('ALTER TABLE reclamation ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CE606404A76ED395 ON reclamation (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE allocation DROP FOREIGN KEY FK_5C44232A71F7E88B');
        $this->addSql('DROP INDEX IDX_5C44232A71F7E88B ON allocation');
        $this->addSql('ALTER TABLE allocation DROP event_id');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA76AB213CC');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA758E0A285');
        $this->addSql('DROP INDEX UNIQ_3BAE0AA76AB213CC ON event');
        $this->addSql('DROP INDEX IDX_3BAE0AA758E0A285 ON event');
        $this->addSql('ALTER TABLE event DROP lieu_id, DROP userid_id');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14A76ED395');
        $this->addSql('DROP INDEX IDX_CFBDFA14A76ED395 ON note');
        $this->addSql('ALTER TABLE note DROP user_id');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404A76ED395');
        $this->addSql('DROP INDEX IDX_CE606404A76ED395 ON reclamation');
        $this->addSql('ALTER TABLE reclamation DROP user_id');
    }
}

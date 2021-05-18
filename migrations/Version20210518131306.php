<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210518131306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('INSERT INTO author (name, email) VALUES (\'unknown\', NULL)');
        $this->addSql('ALTER TABLE post ADD written_by_id INT');
        $this->addSql('UPDATE post SET written_by_id = (SELECT id FROM author WHERE name=\'unknown\')');
        $this->addSql('ALTER TABLE post MODIFY COLUMN written_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DAB69C8EF FOREIGN KEY (written_by_id) REFERENCES author (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DAB69C8EF ON post (written_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DAB69C8EF');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP INDEX IDX_5A8A6C8DAB69C8EF ON post');
        $this->addSql('ALTER TABLE post DROP written_by_id');
    }
}

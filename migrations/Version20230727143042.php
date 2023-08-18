<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230727143042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tweet ADD author_id INT NOT NULL, ADD likes INT NOT NULL, ADD retweets INT NOT NULL, ADD cites INT NOT NULL');
        $this->addSql('ALTER TABLE tweet ADD CONSTRAINT FK_3D660A3BF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3D660A3BF675F31B ON tweet (author_id)');
        $this->addSql('ALTER TABLE user ADD username VARCHAR(255) NOT NULL, ADD photo VARCHAR(255) DEFAULT NULL, ADD followers INT NOT NULL, ADD following INT NOT NULL, CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tweet DROP FOREIGN KEY FK_3D660A3BF675F31B');
        $this->addSql('DROP INDEX IDX_3D660A3BF675F31B ON tweet');
        $this->addSql('ALTER TABLE tweet DROP author_id, DROP likes, DROP retweets, DROP cites');
        $this->addSql('ALTER TABLE user DROP username, DROP photo, DROP followers, DROP following, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}

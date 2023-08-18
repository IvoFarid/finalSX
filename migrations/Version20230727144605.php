<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230727144605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE action_type (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tweet_relations (id INT AUTO_INCREMENT NOT NULL, tweet_id INT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, INDEX IDX_83FD809A1041E39B (tweet_id), INDEX IDX_83FD809AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tweet_relations ADD CONSTRAINT FK_83FD809A1041E39B FOREIGN KEY (tweet_id) REFERENCES tweet (id)');
        $this->addSql('ALTER TABLE tweet_relations ADD CONSTRAINT FK_83FD809AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tweet_relations DROP FOREIGN KEY FK_83FD809A1041E39B');
        $this->addSql('ALTER TABLE tweet_relations DROP FOREIGN KEY FK_83FD809AA76ED395');
        $this->addSql('DROP TABLE action_type');
        $this->addSql('DROP TABLE tweet_relations');
    }
}

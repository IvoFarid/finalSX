<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230727145337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_relations (id INT AUTO_INCREMENT NOT NULL, follower_id INT NOT NULL, following_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_148C329CAC24F853 (follower_id), INDEX IDX_148C329C1816E3A3 (following_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_relations ADD CONSTRAINT FK_148C329CAC24F853 FOREIGN KEY (follower_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_relations ADD CONSTRAINT FK_148C329C1816E3A3 FOREIGN KEY (following_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_relations DROP FOREIGN KEY FK_148C329CAC24F853');
        $this->addSql('ALTER TABLE user_relations DROP FOREIGN KEY FK_148C329C1816E3A3');
        $this->addSql('DROP TABLE user_relations');
    }
}

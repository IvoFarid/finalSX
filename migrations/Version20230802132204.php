<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230802132204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tweet DROP FOREIGN KEY FK_3D660A3B3D3D2749');
        $this->addSql('DROP INDEX IDX_3D660A3B3D3D2749 ON tweet');
        $this->addSql('ALTER TABLE tweet CHANGE children_id parent_tweet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tweet ADD CONSTRAINT FK_3D660A3B8F8C4F4A FOREIGN KEY (parent_tweet_id) REFERENCES tweet (id)');
        $this->addSql('CREATE INDEX IDX_3D660A3B8F8C4F4A ON tweet (parent_tweet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tweet DROP FOREIGN KEY FK_3D660A3B8F8C4F4A');
        $this->addSql('DROP INDEX IDX_3D660A3B8F8C4F4A ON tweet');
        $this->addSql('ALTER TABLE tweet CHANGE parent_tweet_id children_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tweet ADD CONSTRAINT FK_3D660A3B3D3D2749 FOREIGN KEY (children_id) REFERENCES tweet (id)');
        $this->addSql('CREATE INDEX IDX_3D660A3B3D3D2749 ON tweet (children_id)');
    }
}

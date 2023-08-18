<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230802134237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tweet ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tweet ADD CONSTRAINT FK_3D660A3B727ACA70 FOREIGN KEY (parent_id) REFERENCES tweet (id)');
        $this->addSql('CREATE INDEX IDX_3D660A3B727ACA70 ON tweet (parent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tweet DROP FOREIGN KEY FK_3D660A3B727ACA70');
        $this->addSql('DROP INDEX IDX_3D660A3B727ACA70 ON tweet');
        $this->addSql('ALTER TABLE tweet DROP parent_id');
    }
}

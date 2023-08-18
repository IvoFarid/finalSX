<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230727144820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tweet_relations ADD action_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE tweet_relations ADD CONSTRAINT FK_83FD809A1FEE0472 FOREIGN KEY (action_type_id) REFERENCES action_type (id)');
        $this->addSql('CREATE INDEX IDX_83FD809A1FEE0472 ON tweet_relations (action_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tweet_relations DROP FOREIGN KEY FK_83FD809A1FEE0472');
        $this->addSql('DROP INDEX IDX_83FD809A1FEE0472 ON tweet_relations');
        $this->addSql('ALTER TABLE tweet_relations DROP action_type_id');
    }
}

<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180602151258 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE visitante ADD created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE visitante ADD CONSTRAINT FK_E3659823B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E3659823B03A8386 ON visitante (created_by_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE visitante DROP FOREIGN KEY FK_E3659823B03A8386');
        $this->addSql('DROP INDEX UNIQ_E3659823B03A8386 ON visitante');
        $this->addSql('ALTER TABLE visitante DROP created_by_id');
    }
}

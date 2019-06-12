<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180602152903 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE emprestimo ADD created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE emprestimo ADD CONSTRAINT FK_E6813B92B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_E6813B92B03A8386 ON emprestimo (created_by_id)');
        $this->addSql('ALTER TABLE material ADD created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE material ADD CONSTRAINT FK_7CBE7595B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_7CBE7595B03A8386 ON material (created_by_id)');
        $this->addSql('ALTER TABLE tipo_material ADD created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tipo_material ADD CONSTRAINT FK_AA7B2F5CB03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_AA7B2F5CB03A8386 ON tipo_material (created_by_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE emprestimo DROP FOREIGN KEY FK_E6813B92B03A8386');
        $this->addSql('DROP INDEX IDX_E6813B92B03A8386 ON emprestimo');
        $this->addSql('ALTER TABLE emprestimo DROP created_by_id');
        $this->addSql('ALTER TABLE material DROP FOREIGN KEY FK_7CBE7595B03A8386');
        $this->addSql('DROP INDEX IDX_7CBE7595B03A8386 ON material');
        $this->addSql('ALTER TABLE material DROP created_by_id');
        $this->addSql('ALTER TABLE tipo_material DROP FOREIGN KEY FK_AA7B2F5CB03A8386');
        $this->addSql('DROP INDEX IDX_AA7B2F5CB03A8386 ON tipo_material');
        $this->addSql('ALTER TABLE tipo_material DROP created_by_id');
    }
}

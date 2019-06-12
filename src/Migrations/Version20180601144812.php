<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180601144812 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE emprestimo (id INT AUTO_INCREMENT NOT NULL, material_id INT NOT NULL, visitante_id INT DEFAULT NULL, data_emprestimo DATETIME NOT NULL, data_devolucao DATETIME DEFAULT NULL, codpes VARCHAR(255) DEFAULT NULL, INDEX IDX_E6813B92E308AC6F (material_id), INDEX IDX_E6813B92D80AA8AF (visitante_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE emprestimo ADD CONSTRAINT FK_E6813B92E308AC6F FOREIGN KEY (material_id) REFERENCES material (id)');
        $this->addSql('ALTER TABLE emprestimo ADD CONSTRAINT FK_E6813B92D80AA8AF FOREIGN KEY (visitante_id) REFERENCES visitante (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE emprestimo');
    }
}

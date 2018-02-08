<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180208212416 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE simpleshop_product ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE simpleshop_product ADD CONSTRAINT FK_7EA9B5673DA5256D FOREIGN KEY (image_id) REFERENCES content_file (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_7EA9B5673DA5256D ON simpleshop_product (image_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE simpleshop_product DROP FOREIGN KEY FK_7EA9B5673DA5256D');
        $this->addSql('DROP INDEX IDX_7EA9B5673DA5256D ON simpleshop_product');
        $this->addSql('ALTER TABLE simpleshop_product DROP image_id');
    }
}

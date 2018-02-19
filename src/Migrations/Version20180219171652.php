<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180219171652 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE config (id INT AUTO_INCREMENT NOT NULL, code CHAR(255) NOT NULL, value CHAR(255) DEFAULT NULL, description CHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE simpleshop_product (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, image_id INT DEFAULT NULL, active TINYINT(1) NOT NULL, label CHAR(128) NOT NULL, description CHAR(255) DEFAULT NULL, price INT NOT NULL, INDEX IDX_7EA9B56712469DE2 (category_id), INDEX IDX_7EA9B5673DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE content_page (id INT AUTO_INCREMENT NOT NULL, code CHAR(128) NOT NULL, title CHAR(255) NOT NULL, description CHAR(255) DEFAULT NULL, keywords CHAR(255) DEFAULT NULL, text LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE content_text (id INT AUTO_INCREMENT NOT NULL, code CHAR(128) NOT NULL, text LONGTEXT NOT NULL, enabled_parameters LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE simpleshop_order_item (id INT AUTO_INCREMENT NOT NULL, order_id INT DEFAULT NULL, product_id INT DEFAULT NULL, count INT NOT NULL, price INT DEFAULT NULL, INDEX IDX_A0F20098D9F6D38 (order_id), INDEX IDX_A0F20094584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE simpleshop_product ADD CONSTRAINT FK_7EA9B56712469DE2 FOREIGN KEY (category_id) REFERENCES simpleshop_category (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE simpleshop_product ADD CONSTRAINT FK_7EA9B5673DA5256D FOREIGN KEY (image_id) REFERENCES content_file (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE simpleshop_order_item ADD CONSTRAINT FK_A0F20098D9F6D38 FOREIGN KEY (order_id) REFERENCES simpleshop_order (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE simpleshop_order_item ADD CONSTRAINT FK_A0F20094584665A FOREIGN KEY (product_id) REFERENCES simpleshop_product (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE simpleshop_order_item DROP FOREIGN KEY FK_A0F20094584665A');
        $this->addSql('DROP TABLE config');
        $this->addSql('DROP TABLE simpleshop_product');
        $this->addSql('DROP TABLE content_page');
        $this->addSql('DROP TABLE content_text');
        $this->addSql('DROP TABLE simpleshop_order_item');
    }
}

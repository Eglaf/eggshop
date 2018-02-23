<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180223163040 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE config (id INT AUTO_INCREMENT NOT NULL, code CHAR(255) NOT NULL, value CHAR(255) DEFAULT NULL, description CHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE content_file (id INT AUTO_INCREMENT NOT NULL, storage_name CHAR(255) NOT NULL, mime_type CHAR(64) NOT NULL, active TINYINT(1) NOT NULL, label CHAR(255) DEFAULT NULL, description CHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE simpleshop_category (id INT AUTO_INCREMENT NOT NULL, active TINYINT(1) NOT NULL, label CHAR(255) NOT NULL, sequence SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE simpleshop_order (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, status_id INT DEFAULT NULL, shipping_address_id INT DEFAULT NULL, billing_address_id INT DEFAULT NULL, date DATETIME NOT NULL, comment LONGTEXT DEFAULT NULL, INDEX IDX_72E4278BA76ED395 (user_id), INDEX IDX_72E4278B6BF700BD (status_id), INDEX IDX_72E4278B4D4CFF2B (shipping_address_id), INDEX IDX_72E4278B79D0C0E4 (billing_address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_user (id INT AUTO_INCREMENT NOT NULL, name CHAR(64) NOT NULL, email CHAR(128) NOT NULL, phone CHAR(16) NOT NULL, password CHAR(64) NOT NULL, role CHAR(32) NOT NULL, active TINYINT(1) NOT NULL, activation_hash CHAR(16) DEFAULT NULL, UNIQUE INDEX UNIQ_F7129A80E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE simpleshop_product (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, image_id INT DEFAULT NULL, active TINYINT(1) NOT NULL, label CHAR(128) NOT NULL, description CHAR(255) DEFAULT NULL, price INT NOT NULL, sequence SMALLINT NOT NULL, INDEX IDX_7EA9B56712469DE2 (category_id), INDEX IDX_7EA9B5673DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_address (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, title CHAR(255) DEFAULT NULL, city CHAR(255) NOT NULL, zipCode CHAR(16) NOT NULL, street CHAR(128) NOT NULL, houseNumber CHAR(16) NOT NULL, floor CHAR(16) DEFAULT NULL, door CHAR(16) DEFAULT NULL, doorBell CHAR(16) DEFAULT NULL, INDEX IDX_5543718BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE content_page (id INT AUTO_INCREMENT NOT NULL, code CHAR(128) NOT NULL, title CHAR(255) NOT NULL, description CHAR(255) DEFAULT NULL, keywords CHAR(255) DEFAULT NULL, text LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE content_text (id INT AUTO_INCREMENT NOT NULL, code CHAR(128) NOT NULL, text LONGTEXT NOT NULL, enabled_parameters LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE simpleshop_order_item (id INT AUTO_INCREMENT NOT NULL, order_id INT DEFAULT NULL, product_id INT DEFAULT NULL, count INT NOT NULL, price INT DEFAULT NULL, INDEX IDX_A0F20098D9F6D38 (order_id), INDEX IDX_A0F20094584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE simpleshop_order_status (id INT AUTO_INCREMENT NOT NULL, label CHAR(128) NOT NULL, code CHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE simpleshop_order ADD CONSTRAINT FK_72E4278BA76ED395 FOREIGN KEY (user_id) REFERENCES user_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE simpleshop_order ADD CONSTRAINT FK_72E4278B6BF700BD FOREIGN KEY (status_id) REFERENCES simpleshop_order_status (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE simpleshop_order ADD CONSTRAINT FK_72E4278B4D4CFF2B FOREIGN KEY (shipping_address_id) REFERENCES user_address (id)');
        $this->addSql('ALTER TABLE simpleshop_order ADD CONSTRAINT FK_72E4278B79D0C0E4 FOREIGN KEY (billing_address_id) REFERENCES user_address (id)');
        $this->addSql('ALTER TABLE simpleshop_product ADD CONSTRAINT FK_7EA9B56712469DE2 FOREIGN KEY (category_id) REFERENCES simpleshop_category (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE simpleshop_product ADD CONSTRAINT FK_7EA9B5673DA5256D FOREIGN KEY (image_id) REFERENCES content_file (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE user_address ADD CONSTRAINT FK_5543718BA76ED395 FOREIGN KEY (user_id) REFERENCES user_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE simpleshop_order_item ADD CONSTRAINT FK_A0F20098D9F6D38 FOREIGN KEY (order_id) REFERENCES simpleshop_order (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE simpleshop_order_item ADD CONSTRAINT FK_A0F20094584665A FOREIGN KEY (product_id) REFERENCES simpleshop_product (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE simpleshop_product DROP FOREIGN KEY FK_7EA9B5673DA5256D');
        $this->addSql('ALTER TABLE simpleshop_product DROP FOREIGN KEY FK_7EA9B56712469DE2');
        $this->addSql('ALTER TABLE simpleshop_order_item DROP FOREIGN KEY FK_A0F20098D9F6D38');
        $this->addSql('ALTER TABLE simpleshop_order DROP FOREIGN KEY FK_72E4278BA76ED395');
        $this->addSql('ALTER TABLE user_address DROP FOREIGN KEY FK_5543718BA76ED395');
        $this->addSql('ALTER TABLE simpleshop_order_item DROP FOREIGN KEY FK_A0F20094584665A');
        $this->addSql('ALTER TABLE simpleshop_order DROP FOREIGN KEY FK_72E4278B4D4CFF2B');
        $this->addSql('ALTER TABLE simpleshop_order DROP FOREIGN KEY FK_72E4278B79D0C0E4');
        $this->addSql('ALTER TABLE simpleshop_order DROP FOREIGN KEY FK_72E4278B6BF700BD');
        $this->addSql('DROP TABLE config');
        $this->addSql('DROP TABLE content_file');
        $this->addSql('DROP TABLE simpleshop_category');
        $this->addSql('DROP TABLE simpleshop_order');
        $this->addSql('DROP TABLE user_user');
        $this->addSql('DROP TABLE simpleshop_product');
        $this->addSql('DROP TABLE user_address');
        $this->addSql('DROP TABLE content_page');
        $this->addSql('DROP TABLE content_text');
        $this->addSql('DROP TABLE simpleshop_order_item');
        $this->addSql('DROP TABLE simpleshop_order_status');
    }
}

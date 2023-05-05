<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230504061426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE purchase (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, user_id INT NOT NULL, date DATETIME NOT NULL, quantity INT NOT NULL, INDEX IDX_6117D13B4584665A (product_id), INDEX IDX_6117D13BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13B4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY cart_item_product_id_fk');
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY cart_item_user_id_fk');
        $this->addSql('DROP INDEX cart_item_user_id_fk ON cart_item');
        $this->addSql('CREATE INDEX IDX_F0FE2527A76ED395 ON cart_item (user_id)');
        $this->addSql('DROP INDEX cart_item_product_id_fk ON cart_item');
        $this->addSql('CREATE INDEX IDX_F0FE25274584665A ON cart_item (product_id)');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT cart_item_product_id_fk FOREIGN KEY (product_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT cart_item_user_id_fk FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY category_user_id_fk');
        $this->addSql('DROP INDEX category_user_id_fk ON category');
        $this->addSql('CREATE INDEX IDX_64C19C1A76ED395 ON category (user_id)');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT category_user_id_fk FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY product_user_fk');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13B4584665A');
        $this->addSql('ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13BA76ED395');
        $this->addSql('DROP TABLE purchase');
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE2527A76ED395');
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE25274584665A');
        $this->addSql('DROP INDEX idx_f0fe2527a76ed395 ON cart_item');
        $this->addSql('CREATE INDEX cart_item_user_id_fk ON cart_item (user_id)');
        $this->addSql('DROP INDEX idx_f0fe25274584665a ON cart_item');
        $this->addSql('CREATE INDEX cart_item_product_id_fk ON cart_item (product_id)');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE2527A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25274584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1A76ED395');
        $this->addSql('DROP INDEX idx_64c19c1a76ed395 ON category');
        $this->addSql('CREATE INDEX category_user_id_fk ON category (user_id)');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }
}

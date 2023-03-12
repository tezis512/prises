<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230312133107 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, vendor_code VARCHAR(255) NOT NULL, vendor_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_offer (category_id INT NOT NULL, offer_id INT NOT NULL, INDEX IDX_C1E5C87712469DE2 (category_id), INDEX IDX_C1E5C87753C674EE (offer_id), PRIMARY KEY(category_id, offer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_offer (product_id INT NOT NULL, offer_id INT NOT NULL, INDEX IDX_888AFC624584665A (product_id), INDEX IDX_888AFC6253C674EE (offer_id), PRIMARY KEY(product_id, offer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_offer ADD CONSTRAINT FK_C1E5C87712469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_offer ADD CONSTRAINT FK_C1E5C87753C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_offer ADD CONSTRAINT FK_888AFC624584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_offer ADD CONSTRAINT FK_888AFC6253C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_offer DROP FOREIGN KEY FK_C1E5C87712469DE2');
        $this->addSql('ALTER TABLE category_offer DROP FOREIGN KEY FK_C1E5C87753C674EE');
        $this->addSql('ALTER TABLE product_offer DROP FOREIGN KEY FK_888AFC624584665A');
        $this->addSql('ALTER TABLE product_offer DROP FOREIGN KEY FK_888AFC6253C674EE');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_offer');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_offer');
    }
}

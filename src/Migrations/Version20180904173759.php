<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180904173759 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE advertisement (id INT AUTO_INCREMENT NOT NULL, region_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, price NUMERIC(10, 0) NOT NULL, INDEX IDX_C95F6AEE98260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE advertisement_category (advertisement_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_78296C89A1FBF71B (advertisement_id), INDEX IDX_78296C8912469DE2 (category_id), PRIMARY KEY(advertisement_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE advertisement_sub_category (advertisement_id INT NOT NULL, sub_category_id INT NOT NULL, INDEX IDX_1F4EEE28A1FBF71B (advertisement_id), INDEX IDX_1F4EEE28F7BFE87C (sub_category_id), PRIMARY KEY(advertisement_id, sub_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sub_category (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_BCE3F79812469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE advertisement ADD CONSTRAINT FK_C95F6AEE98260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE advertisement_category ADD CONSTRAINT FK_78296C89A1FBF71B FOREIGN KEY (advertisement_id) REFERENCES advertisement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE advertisement_category ADD CONSTRAINT FK_78296C8912469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE advertisement_sub_category ADD CONSTRAINT FK_1F4EEE28A1FBF71B FOREIGN KEY (advertisement_id) REFERENCES advertisement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE advertisement_sub_category ADD CONSTRAINT FK_1F4EEE28F7BFE87C FOREIGN KEY (sub_category_id) REFERENCES sub_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sub_category ADD CONSTRAINT FK_BCE3F79812469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE advertisement_category DROP FOREIGN KEY FK_78296C89A1FBF71B');
        $this->addSql('ALTER TABLE advertisement_sub_category DROP FOREIGN KEY FK_1F4EEE28A1FBF71B');
        $this->addSql('ALTER TABLE advertisement_category DROP FOREIGN KEY FK_78296C8912469DE2');
        $this->addSql('ALTER TABLE sub_category DROP FOREIGN KEY FK_BCE3F79812469DE2');
        $this->addSql('ALTER TABLE advertisement DROP FOREIGN KEY FK_C95F6AEE98260155');
        $this->addSql('ALTER TABLE advertisement_sub_category DROP FOREIGN KEY FK_1F4EEE28F7BFE87C');
        $this->addSql('DROP TABLE advertisement');
        $this->addSql('DROP TABLE advertisement_category');
        $this->addSql('DROP TABLE advertisement_sub_category');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE sub_category');
    }
}

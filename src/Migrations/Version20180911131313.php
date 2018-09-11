<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180911131313 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE villes_france_free');
        $this->addSql('ALTER TABLE advertisement DROP zip_code, DROP city');
        $this->addSql('ALTER TABLE user CHANGE salt salt VARCHAR(255) DEFAULT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL, CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT NULL, CHANGE password_requested_at password_requested_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE villes_france_free (ville_id INT UNSIGNED AUTO_INCREMENT NOT NULL, ville_departement VARCHAR(3) DEFAULT \'NULL\' COLLATE utf8_general_ci, ville_slug VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_general_ci, ville_nom VARCHAR(45) DEFAULT \'NULL\' COLLATE utf8_general_ci, ville_nom_simple VARCHAR(45) DEFAULT \'NULL\' COLLATE utf8_general_ci, ville_nom_reel VARCHAR(45) DEFAULT \'NULL\' COLLATE utf8_general_ci, ville_code_postal VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_general_ci, UNIQUE INDEX ville_slug (ville_slug), INDEX ville_departement (ville_departement), INDEX ville_nom (ville_nom), INDEX ville_nom_reel (ville_nom_reel), INDEX ville_code_postal (ville_code_postal), INDEX ville_nom_simple (ville_nom_simple), PRIMARY KEY(ville_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE advertisement ADD zip_code VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD city VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user CHANGE salt salt VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE last_login last_login DATETIME DEFAULT \'NULL\', CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE password_requested_at password_requested_at DATETIME DEFAULT \'NULL\'');
    }
}

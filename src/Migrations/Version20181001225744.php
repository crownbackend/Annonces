<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181001225744 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE messages ADD from_id_id INT NOT NULL, ADD to_id_id INT NOT NULL, CHANGE read_at read_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E964632BB48 FOREIGN KEY (from_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E967478AF67 FOREIGN KEY (to_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_DB021E964632BB48 ON messages (from_id_id)');
        $this->addSql('CREATE INDEX IDX_DB021E967478AF67 ON messages (to_id_id)');
        $this->addSql('ALTER TABLE user CHANGE salt salt VARCHAR(255) DEFAULT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL, CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT NULL, CHANGE password_requested_at password_requested_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E964632BB48');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E967478AF67');
        $this->addSql('DROP INDEX IDX_DB021E964632BB48 ON messages');
        $this->addSql('DROP INDEX IDX_DB021E967478AF67 ON messages');
        $this->addSql('ALTER TABLE messages DROP from_id_id, DROP to_id_id, CHANGE read_at read_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE salt salt VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE last_login last_login DATETIME DEFAULT \'NULL\', CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE password_requested_at password_requested_at DATETIME DEFAULT \'NULL\'');
    }
}

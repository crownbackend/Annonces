<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180924233528 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE conversation_users_conversation (conversation_users_id INT NOT NULL, conversation_id INT NOT NULL, INDEX IDX_560EF88987FD0B2D (conversation_users_id), INDEX IDX_560EF8899AC0396 (conversation_id), PRIMARY KEY(conversation_users_id, conversation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE conversation_users_conversation ADD CONSTRAINT FK_560EF88987FD0B2D FOREIGN KEY (conversation_users_id) REFERENCES conversation_users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE conversation_users_conversation ADD CONSTRAINT FK_560EF8899AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE conversation_users ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE conversation_users ADD CONSTRAINT FK_5586C5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5586C5A76ED395 ON conversation_users (user_id)');
        $this->addSql('ALTER TABLE messages ADD conversation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E969AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id)');
        $this->addSql('CREATE INDEX IDX_DB021E969AC0396 ON messages (conversation_id)');
        $this->addSql('ALTER TABLE user CHANGE salt salt VARCHAR(255) DEFAULT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL, CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT NULL, CHANGE password_requested_at password_requested_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE conversation_users_conversation');
        $this->addSql('ALTER TABLE conversation_users DROP FOREIGN KEY FK_5586C5A76ED395');
        $this->addSql('DROP INDEX IDX_5586C5A76ED395 ON conversation_users');
        $this->addSql('ALTER TABLE conversation_users DROP user_id');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E969AC0396');
        $this->addSql('DROP INDEX IDX_DB021E969AC0396 ON messages');
        $this->addSql('ALTER TABLE messages DROP conversation_id');
        $this->addSql('ALTER TABLE user CHANGE salt salt VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE last_login last_login DATETIME DEFAULT \'NULL\', CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE password_requested_at password_requested_at DATETIME DEFAULT \'NULL\'');
    }
}

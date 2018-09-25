<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180925000013 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE conversation_users_conversation DROP FOREIGN KEY FK_560EF8899AC0396');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E969AC0396');
        $this->addSql('ALTER TABLE conversation_users_conversation DROP FOREIGN KEY FK_560EF88987FD0B2D');
        $this->addSql('DROP TABLE conversation');
        $this->addSql('DROP TABLE conversation_users');
        $this->addSql('DROP TABLE conversation_users_conversation');
        $this->addSql('DROP TABLE messages');
        $this->addSql('ALTER TABLE user CHANGE salt salt VARCHAR(255) DEFAULT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL, CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT NULL, CHANGE password_requested_at password_requested_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE conversation (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conversation_users (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, INDEX IDX_5586C5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conversation_users_conversation (conversation_users_id INT NOT NULL, conversation_id INT NOT NULL, INDEX IDX_560EF88987FD0B2D (conversation_users_id), INDEX IDX_560EF8899AC0396 (conversation_id), PRIMARY KEY(conversation_users_id, conversation_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messages (id INT AUTO_INCREMENT NOT NULL, conversation_id INT DEFAULT NULL, content LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, created_at DATETIME NOT NULL, INDEX IDX_DB021E969AC0396 (conversation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE conversation_users ADD CONSTRAINT FK_5586C5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE conversation_users_conversation ADD CONSTRAINT FK_560EF88987FD0B2D FOREIGN KEY (conversation_users_id) REFERENCES conversation_users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE conversation_users_conversation ADD CONSTRAINT FK_560EF8899AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E969AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id)');
        $this->addSql('ALTER TABLE user CHANGE salt salt VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE last_login last_login DATETIME DEFAULT \'NULL\', CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE password_requested_at password_requested_at DATETIME DEFAULT \'NULL\'');
    }
}

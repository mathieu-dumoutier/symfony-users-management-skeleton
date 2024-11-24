<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241119192251 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE "group" (id SERIAL NOT NULL, name VARCHAR(25) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE group_user (group_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(group_id, user_id))');
        $this->addSql('CREATE INDEX IDX_A4C98D39FE54D947 ON group_user (group_id)');
        $this->addSql('CREATE INDEX IDX_A4C98D39A76ED395 ON group_user (user_id)');
        $this->addSql('CREATE TABLE reset_password_request (id SERIAL NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7CE748AA76ED395 ON reset_password_request (user_id)');
        $this->addSql('COMMENT ON COLUMN reset_password_request.requested_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN reset_password_request.expires_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE role (id SERIAL NOT NULL, key VARCHAR(50) NOT NULL, name VARCHAR(50) NOT NULL, subject VARCHAR(20) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE role_group (role_id INT NOT NULL, group_id INT NOT NULL, PRIMARY KEY(role_id, group_id))');
        $this->addSql('CREATE INDEX IDX_9A1CACEAD60322AC ON role_group (role_id)');
        $this->addSql('CREATE INDEX IDX_9A1CACEAFE54D947 ON role_group (group_id)');
        $this->addSql('CREATE TABLE role_user (role_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(role_id, user_id))');
        $this->addSql('CREATE INDEX IDX_332CA4DDD60322AC ON role_user (role_id)');
        $this->addSql('CREATE INDEX IDX_332CA4DDA76ED395 ON role_user (user_id)');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) DEFAULT NULL, is_verified BOOLEAN NOT NULL, disabled_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_GROUP_NAME ON "group" (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ROLE_KEY ON role (key)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ROLE_NAME ON role (name)');
        $this->addSql('COMMENT ON COLUMN "user".disabled_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('CREATE TABLE rememberme_token (series VARCHAR(88) NOT NULL, value VARCHAR(88) NOT NULL, lastUsed TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, class VARCHAR(100) NOT NULL, username VARCHAR(200) NOT NULL, PRIMARY KEY(series))');
        $this->addSql('COMMENT ON COLUMN rememberme_token.lastUsed IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE group_user ADD CONSTRAINT FK_A4C98D39FE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_user ADD CONSTRAINT FK_A4C98D39A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE role_group ADD CONSTRAINT FK_9A1CACEAD60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE role_group ADD CONSTRAINT FK_9A1CACEAFE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE role_user ADD CONSTRAINT FK_332CA4DDD60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE role_user ADD CONSTRAINT FK_332CA4DDA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql("INSERT INTO role (key, name) VALUES ('ROLE_SUPER_ADMIN', 'Full privileges');");
        $this->addSql("INSERT INTO role (key, name) VALUES ('ROLE_ALLOWED_TO_SWITCH', 'Allowed to impersonate');");
        $this->addSql("INSERT INTO role (key, name) VALUES ('ROLE_ADMIN', 'Admin');");
        $this->addSql("INSERT INTO role (key, name) VALUES ('ROLE_USER', 'User');");
        $this->addSql("INSERT INTO \"group\" (name) VALUES ('Super administrators');");
        $this->addSql("INSERT INTO \"group\" (name) VALUES ('Administrators');");
        $this->addSql("INSERT INTO \"group\" (name) VALUES ('Users');");
        $this->addSql('INSERT INTO role_group (role_id, group_id) VALUES (1, 1);');
        $this->addSql('INSERT INTO role_group (role_id, group_id) VALUES (2, 1);');
        $this->addSql('INSERT INTO role_group (role_id, group_id) VALUES (3, 1);');
        $this->addSql('INSERT INTO role_group (role_id, group_id) VALUES (3, 2);');
        $this->addSql('INSERT INTO role_group (role_id, group_id) VALUES (4, 1);');
        $this->addSql('INSERT INTO role_group (role_id, group_id) VALUES (4, 2);');
        $this->addSql('INSERT INTO role_group (role_id, group_id) VALUES (4, 3);');
        $this->addSql('CREATE TABLE configuration (key VARCHAR(35) NOT NULL, value VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(key))');
        $this->addSql('INSERT INTO configuration (key, value, description) VALUES (\'APP_NAME\', \'Symfony Users Management Skeleton\', \'Name of your application or site. This name is used in e-mails, for example.\')');
        $this->addSql('INSERT INTO configuration (key, value, description) VALUES (\'MAILER_DSN\', \'smtp://mailer:1025\', \'SMTP configuration in DSN format for sending e-mails\')');
        $this->addSql('INSERT INTO configuration (key, value, description) VALUES (\'SENDER_EMAIL\', \'mailer@your-domain.com\', \'E-mail address for sending e-mails\')');
        $this->addSql('INSERT INTO configuration (key, value, description) VALUES (\'SENDER_NAME\', \'Acme Mail Bot\', \'Name sending e-mails\')');
        $this->addSql('INSERT INTO configuration (key, value, description) VALUES (\'REGISTRATION_ENABLED\', \'1\', \'You can create your own account to access the site. 1 for enabled. 0 for disabled.\')');
        $this->addSql('INSERT INTO configuration (key, value, description) VALUES (\'RESET_PASSWORD_REQUEST_LIFETIME\', \'3600\', \'Validity time of links sent by e-mail to reset your password (in seconds).\')');
        $this->addSql('INSERT INTO configuration (key, value, description) VALUES (\'CHOOSE_PASSWORD_LIFETIME\', \'604800\', \'Validity time of links sent by e-mail to choose your password (in seconds).\')');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE configuration');
        $this->addSql('ALTER TABLE group_user DROP CONSTRAINT FK_A4C98D39FE54D947');
        $this->addSql('ALTER TABLE group_user DROP CONSTRAINT FK_A4C98D39A76ED395');
        $this->addSql('ALTER TABLE reset_password_request DROP CONSTRAINT FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE role_group DROP CONSTRAINT FK_9A1CACEAD60322AC');
        $this->addSql('ALTER TABLE role_group DROP CONSTRAINT FK_9A1CACEAFE54D947');
        $this->addSql('ALTER TABLE role_user DROP CONSTRAINT FK_332CA4DDD60322AC');
        $this->addSql('ALTER TABLE role_user DROP CONSTRAINT FK_332CA4DDA76ED395');
        $this->addSql('TRUNCATE TABLE role_group');
        $this->addSql('TRUNCATE TABLE "group"');
        $this->addSql('TRUNCATE TABLE role');
        $this->addSql('DROP TABLE "group"');
        $this->addSql('DROP TABLE group_user');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE role_group');
        $this->addSql('DROP TABLE role_user');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('DROP TABLE rememberme_token');
    }
}

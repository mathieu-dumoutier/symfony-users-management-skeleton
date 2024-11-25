<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241125122510 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE configuration ADD created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE configuration ADD updated_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE configuration ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE configuration ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE configuration ADD CONSTRAINT FK_A5E2A5D7B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE configuration ADD CONSTRAINT FK_A5E2A5D7896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_A5E2A5D7B03A8386 ON configuration (created_by_id)');
        $this->addSql('CREATE INDEX IDX_A5E2A5D7896DBBDE ON configuration (updated_by_id)');
        $this->addSql('ALTER TABLE "group" ADD created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "group" ADD updated_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "group" ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE "group" ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE "group" ADD CONSTRAINT FK_6DC044C5B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "group" ADD CONSTRAINT FK_6DC044C5896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6DC044C5B03A8386 ON "group" (created_by_id)');
        $this->addSql('CREATE INDEX IDX_6DC044C5896DBBDE ON "group" (updated_by_id)');
        $this->addSql('ALTER TABLE reset_password_request ADD created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reset_password_request ADD updated_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reset_password_request ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE reset_password_request ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AB03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748A896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_7CE748AB03A8386 ON reset_password_request (created_by_id)');
        $this->addSql('CREATE INDEX IDX_7CE748A896DBBDE ON reset_password_request (updated_by_id)');
        $this->addSql('ALTER TABLE role ADD created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE role ADD updated_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE role ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE role ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE role ADD CONSTRAINT FK_57698A6AB03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE role ADD CONSTRAINT FK_57698A6A896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_57698A6AB03A8386 ON role (created_by_id)');
        $this->addSql('CREATE INDEX IDX_57698A6A896DBBDE ON role (updated_by_id)');
        $this->addSql('ALTER TABLE "user" ADD created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD updated_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8D93D649B03A8386 ON "user" (created_by_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649896DBBDE ON "user" (updated_by_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649B03A8386');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649896DBBDE');
        $this->addSql('DROP INDEX IDX_8D93D649B03A8386');
        $this->addSql('DROP INDEX IDX_8D93D649896DBBDE');
        $this->addSql('ALTER TABLE "user" DROP created_by_id');
        $this->addSql('ALTER TABLE "user" DROP updated_by_id');
        $this->addSql('ALTER TABLE "user" DROP created_at');
        $this->addSql('ALTER TABLE "user" DROP updated_at');
        $this->addSql('ALTER TABLE "group" DROP CONSTRAINT FK_6DC044C5B03A8386');
        $this->addSql('ALTER TABLE "group" DROP CONSTRAINT FK_6DC044C5896DBBDE');
        $this->addSql('DROP INDEX IDX_6DC044C5B03A8386');
        $this->addSql('DROP INDEX IDX_6DC044C5896DBBDE');
        $this->addSql('ALTER TABLE "group" DROP created_by_id');
        $this->addSql('ALTER TABLE "group" DROP updated_by_id');
        $this->addSql('ALTER TABLE "group" DROP created_at');
        $this->addSql('ALTER TABLE "group" DROP updated_at');
        $this->addSql('ALTER TABLE configuration DROP CONSTRAINT FK_A5E2A5D7B03A8386');
        $this->addSql('ALTER TABLE configuration DROP CONSTRAINT FK_A5E2A5D7896DBBDE');
        $this->addSql('DROP INDEX IDX_A5E2A5D7B03A8386');
        $this->addSql('DROP INDEX IDX_A5E2A5D7896DBBDE');
        $this->addSql('ALTER TABLE configuration DROP created_by_id');
        $this->addSql('ALTER TABLE configuration DROP updated_by_id');
        $this->addSql('ALTER TABLE configuration DROP created_at');
        $this->addSql('ALTER TABLE configuration DROP updated_at');
        $this->addSql('ALTER TABLE role DROP CONSTRAINT FK_57698A6AB03A8386');
        $this->addSql('ALTER TABLE role DROP CONSTRAINT FK_57698A6A896DBBDE');
        $this->addSql('DROP INDEX IDX_57698A6AB03A8386');
        $this->addSql('DROP INDEX IDX_57698A6A896DBBDE');
        $this->addSql('ALTER TABLE role DROP created_by_id');
        $this->addSql('ALTER TABLE role DROP updated_by_id');
        $this->addSql('ALTER TABLE role DROP created_at');
        $this->addSql('ALTER TABLE role DROP updated_at');
        $this->addSql('ALTER TABLE reset_password_request DROP CONSTRAINT FK_7CE748AB03A8386');
        $this->addSql('ALTER TABLE reset_password_request DROP CONSTRAINT FK_7CE748A896DBBDE');
        $this->addSql('DROP INDEX IDX_7CE748AB03A8386');
        $this->addSql('DROP INDEX IDX_7CE748A896DBBDE');
        $this->addSql('ALTER TABLE reset_password_request DROP created_by_id');
        $this->addSql('ALTER TABLE reset_password_request DROP updated_by_id');
        $this->addSql('ALTER TABLE reset_password_request DROP created_at');
        $this->addSql('ALTER TABLE reset_password_request DROP updated_at');
    }
}

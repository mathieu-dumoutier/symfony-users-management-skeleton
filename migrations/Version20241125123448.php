<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241125123448 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE configuration ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE configuration ALTER updated_at DROP DEFAULT');
        $this->addSql('ALTER TABLE "group" ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE "group" ALTER updated_at DROP DEFAULT');
        $this->addSql('ALTER TABLE reset_password_request ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE reset_password_request ALTER updated_at DROP DEFAULT');
        $this->addSql('ALTER TABLE role ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE role ALTER updated_at DROP DEFAULT');
        $this->addSql('ALTER TABLE "user" ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE "user" ALTER updated_at DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "group" ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE "group" ALTER updated_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE role ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE role ALTER updated_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE "user" ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE "user" ALTER updated_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE reset_password_request ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE reset_password_request ALTER updated_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE configuration ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE configuration ALTER updated_at SET DEFAULT CURRENT_TIMESTAMP');
    }
}

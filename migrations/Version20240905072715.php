<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240905072715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD campus_id INT NOT NULL, ADD photo VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649AF5D55E1 FOREIGN KEY (campus_id) REFERENCES campus (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649AF5D55E1 ON user (campus_id)');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_identifier_name_surname TO UNIQ_IDENTIFIER_PSEUDO');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649AF5D55E1');
        $this->addSql('DROP INDEX IDX_8D93D649AF5D55E1 ON `user`');
        $this->addSql('ALTER TABLE `user` DROP campus_id, DROP photo');
        $this->addSql('ALTER TABLE `user` RENAME INDEX uniq_identifier_pseudo TO UNIQ_IDENTIFIER_NAME_SURNAME');
    }
}

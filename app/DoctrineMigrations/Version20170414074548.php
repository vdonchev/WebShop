<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170414074548 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE products_order ADD user_id INT NOT NULL, DROP user');
        $this->addSql('ALTER TABLE products_order ADD CONSTRAINT FK_15706D48A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_15706D48A76ED395 ON products_order (user_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE products_order DROP FOREIGN KEY FK_15706D48A76ED395');
        $this->addSql('DROP INDEX IDX_15706D48A76ED395 ON products_order');
        $this->addSql('ALTER TABLE products_order ADD user VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP user_id');
    }
}

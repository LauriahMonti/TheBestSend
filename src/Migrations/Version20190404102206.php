<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190404102206 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE category_ad');
        $this->addSql('ALTER TABLE category ADD ads_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1FE52BF81 FOREIGN KEY (ads_id) REFERENCES ad (id)');
        $this->addSql('CREATE INDEX IDX_64C19C1FE52BF81 ON category (ads_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category_ad (category_id INT NOT NULL, ad_id INT NOT NULL, INDEX IDX_DA9F17EA12469DE2 (category_id), INDEX IDX_DA9F17EA4F34D596 (ad_id), PRIMARY KEY(category_id, ad_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE category_ad ADD CONSTRAINT FK_DA9F17EA12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_ad ADD CONSTRAINT FK_DA9F17EA4F34D596 FOREIGN KEY (ad_id) REFERENCES ad (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1FE52BF81');
        $this->addSql('DROP INDEX IDX_64C19C1FE52BF81 ON category');
        $this->addSql('ALTER TABLE category DROP ads_id');
    }
}

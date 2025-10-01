<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251001120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create annonce_image table for gallery images linked to offres/demandes';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE annonce_image (id INT AUTO_INCREMENT NOT NULL, offre_id INT DEFAULT NULL, demande_id INT DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_A17D932553C674EE (offre_id), INDEX IDX_A17D932519039F4A (demande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonce_image ADD CONSTRAINT FK_A17D932553C674EE FOREIGN KEY (offre_id) REFERENCES offre_de_travail (id)');
        $this->addSql('ALTER TABLE annonce_image ADD CONSTRAINT FK_A17D932519039F4A FOREIGN KEY (demande_id) REFERENCES demande_de_travail (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE annonce_image DROP FOREIGN KEY FK_A17D932553C674EE');
        $this->addSql('ALTER TABLE annonce_image DROP FOREIGN KEY FK_A17D932519039F4A');
        $this->addSql('DROP TABLE annonce_image');
    }
}

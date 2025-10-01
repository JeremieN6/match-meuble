<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251001103915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce_image RENAME INDEX idx_a17d932553c674ee TO IDX_D2B0CFC04CC8505A');
        $this->addSql('ALTER TABLE annonce_image RENAME INDEX idx_a17d932519039f4a TO IDX_D2B0CFC080E95E18');
        $this->addSql('ALTER TABLE demande_de_travail ADD furniture_type VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE offre_de_travail ADD furniture_type VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce_image RENAME INDEX idx_d2b0cfc04cc8505a TO IDX_A17D932553C674EE');
        $this->addSql('ALTER TABLE annonce_image RENAME INDEX idx_d2b0cfc080e95e18 TO IDX_A17D932519039F4A');
        $this->addSql('ALTER TABLE demande_de_travail DROP furniture_type');
        $this->addSql('ALTER TABLE offre_de_travail DROP furniture_type');
    }
}

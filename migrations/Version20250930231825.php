<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250930231825 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favorite_demande (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, demande_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7BC079CBA76ED395 (user_id), INDEX IDX_7BC079CB80E95E18 (demande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorite_offre (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, offre_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_190EAD4BA76ED395 (user_id), INDEX IDX_190EAD4B4CC8505A (offre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favorite_demande ADD CONSTRAINT FK_7BC079CBA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE favorite_demande ADD CONSTRAINT FK_7BC079CB80E95E18 FOREIGN KEY (demande_id) REFERENCES demande_de_travail (id)');
        $this->addSql('ALTER TABLE favorite_offre ADD CONSTRAINT FK_190EAD4BA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE favorite_offre ADD CONSTRAINT FK_190EAD4B4CC8505A FOREIGN KEY (offre_id) REFERENCES offre_de_travail (id)');
        $this->addSql('ALTER TABLE demande_de_travail ADD slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE offre_de_travail ADD slug VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorite_demande DROP FOREIGN KEY FK_7BC079CBA76ED395');
        $this->addSql('ALTER TABLE favorite_demande DROP FOREIGN KEY FK_7BC079CB80E95E18');
        $this->addSql('ALTER TABLE favorite_offre DROP FOREIGN KEY FK_190EAD4BA76ED395');
        $this->addSql('ALTER TABLE favorite_offre DROP FOREIGN KEY FK_190EAD4B4CC8505A');
        $this->addSql('DROP TABLE favorite_demande');
        $this->addSql('DROP TABLE favorite_offre');
        $this->addSql('ALTER TABLE demande_de_travail DROP slug');
        $this->addSql('ALTER TABLE offre_de_travail DROP slug');
    }
}

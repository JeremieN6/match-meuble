<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231110152328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE demande_de_travail (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, titre VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, disponibilite VARCHAR(255) DEFAULT NULL, salaire INT DEFAULT NULL, zone_action VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_5A5D46999D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluation (id INT AUTO_INCREMENT NOT NULL, user_id_auteur_id INT DEFAULT NULL, user_id_cible_id INT DEFAULT NULL, note INT DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, INDEX IDX_1323A5756042AFC3 (user_id_auteur_id), INDEX IDX_1323A57550AE7B09 (user_id_cible_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invoice (id INT AUTO_INCREMENT NOT NULL, subscription_id INT DEFAULT NULL, stripe_id VARCHAR(255) DEFAULT NULL, amount_paid INT DEFAULT NULL, number VARCHAR(255) DEFAULT NULL, hosted_invoice_url VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_906517449A1887DC (subscription_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, expediteur_id_id INT DEFAULT NULL, destinataire_id_id INT DEFAULT NULL, contenu_message LONGTEXT DEFAULT NULL, date_envoi DATE DEFAULT NULL, INDEX IDX_B6BD307FADA744BA (expediteur_id_id), INDEX IDX_B6BD307FE08F0A5A (destinataire_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletter (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, message_notif LONGTEXT DEFAULT NULL, date_notification DATE DEFAULT NULL, lu TINYINT(1) DEFAULT NULL, INDEX IDX_BF5476CA9D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre_de_travail (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, status_id INT DEFAULT NULL, titre VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, localisation VARCHAR(255) DEFAULT NULL, remuneration INT DEFAULT NULL, date_debut_montage DATE DEFAULT NULL, date_fin_montage DATE DEFAULT NULL, evaluation INT DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_81CF2B1D9D86650F (user_id_id), INDEX IDX_81CF2B1D6BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plan (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, stripe_id VARCHAR(255) DEFAULT NULL, prix INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, payment_link VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status_offre (id INT AUTO_INCREMENT NOT NULL, nom_status VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, plan_id INT DEFAULT NULL, user_id INT DEFAULT NULL, stripe_id VARCHAR(255) DEFAULT NULL, current_period_start DATETIME NOT NULL, current_period_end DATETIME NOT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_A3C664D3E899029B (plan_id), INDEX IDX_A3C664D3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_de_meuble (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(100) DEFAULT NULL, prenom VARCHAR(125) DEFAULT NULL, telephone VARCHAR(30) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, code_postal VARCHAR(10) DEFAULT NULL, ville VARCHAR(200) DEFAULT NULL, pseudo VARCHAR(100) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, file VARCHAR(255) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, reset_token VARCHAR(100) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATE DEFAULT NULL, stripe_id VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE demande_de_travail ADD CONSTRAINT FK_5A5D46999D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A5756042AFC3 FOREIGN KEY (user_id_auteur_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57550AE7B09 FOREIGN KEY (user_id_cible_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517449A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FADA744BA FOREIGN KEY (expediteur_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FE08F0A5A FOREIGN KEY (destinataire_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA9D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE offre_de_travail ADD CONSTRAINT FK_81CF2B1D9D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE offre_de_travail ADD CONSTRAINT FK_81CF2B1D6BF700BD FOREIGN KEY (status_id) REFERENCES status_offre (id)');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3E899029B FOREIGN KEY (plan_id) REFERENCES plan (id)');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande_de_travail DROP FOREIGN KEY FK_5A5D46999D86650F');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A5756042AFC3');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A57550AE7B09');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_906517449A1887DC');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FADA744BA');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FE08F0A5A');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA9D86650F');
        $this->addSql('ALTER TABLE offre_de_travail DROP FOREIGN KEY FK_81CF2B1D9D86650F');
        $this->addSql('ALTER TABLE offre_de_travail DROP FOREIGN KEY FK_81CF2B1D6BF700BD');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D3E899029B');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D3A76ED395');
        $this->addSql('DROP TABLE demande_de_travail');
        $this->addSql('DROP TABLE evaluation');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE newsletter');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE offre_de_travail');
        $this->addSql('DROP TABLE plan');
        $this->addSql('DROP TABLE status_offre');
        $this->addSql('DROP TABLE subscription');
        $this->addSql('DROP TABLE type_de_meuble');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

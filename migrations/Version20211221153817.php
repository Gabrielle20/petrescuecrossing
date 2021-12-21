<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211221153817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, total INT NOT NULL, statut INT NOT NULL, INDEX IDX_24CC0DF29D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prix INT NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produits_panier (id INT AUTO_INCREMENT NOT NULL, prix INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produits_panier_produit (produits_panier_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_9F751D8E9F5F177C (produits_panier_id), INDEX IDX_9F751D8EF347EFB (produit_id), PRIMARY KEY(produits_panier_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produits_panier_panier (produits_panier_id INT NOT NULL, panier_id INT NOT NULL, INDEX IDX_921099CD9F5F177C (produits_panier_id), INDEX IDX_921099CDF77D927C (panier_id), PRIMARY KEY(produits_panier_id, panier_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF29D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE produits_panier_produit ADD CONSTRAINT FK_9F751D8E9F5F177C FOREIGN KEY (produits_panier_id) REFERENCES produits_panier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produits_panier_produit ADD CONSTRAINT FK_9F751D8EF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produits_panier_panier ADD CONSTRAINT FK_921099CD9F5F177C FOREIGN KEY (produits_panier_id) REFERENCES produits_panier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produits_panier_panier ADD CONSTRAINT FK_921099CDF77D927C FOREIGN KEY (panier_id) REFERENCES panier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE animal ADD date_arrive DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produits_panier_panier DROP FOREIGN KEY FK_921099CDF77D927C');
        $this->addSql('ALTER TABLE produits_panier_produit DROP FOREIGN KEY FK_9F751D8EF347EFB');
        $this->addSql('ALTER TABLE produits_panier_produit DROP FOREIGN KEY FK_9F751D8E9F5F177C');
        $this->addSql('ALTER TABLE produits_panier_panier DROP FOREIGN KEY FK_921099CD9F5F177C');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE produits_panier');
        $this->addSql('DROP TABLE produits_panier_produit');
        $this->addSql('DROP TABLE produits_panier_panier');
        $this->addSql('ALTER TABLE animal DROP date_arrive');
    }
}

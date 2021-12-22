<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211222115605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE produits_panier_panier');
        $this->addSql('DROP TABLE produits_panier_produit');
        $this->addSql('ALTER TABLE produits_panier ADD panier_id INT NOT NULL');
        $this->addSql('ALTER TABLE produits_panier ADD CONSTRAINT FK_B5DD1611F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)');
        $this->addSql('CREATE INDEX IDX_B5DD1611F77D927C ON produits_panier (panier_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE produits_panier_panier (produits_panier_id INT NOT NULL, panier_id INT NOT NULL, INDEX IDX_921099CD9F5F177C (produits_panier_id), INDEX IDX_921099CDF77D927C (panier_id), PRIMARY KEY(produits_panier_id, panier_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE produits_panier_produit (produits_panier_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_9F751D8E9F5F177C (produits_panier_id), INDEX IDX_9F751D8EF347EFB (produit_id), PRIMARY KEY(produits_panier_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE produits_panier_panier ADD CONSTRAINT FK_921099CD9F5F177C FOREIGN KEY (produits_panier_id) REFERENCES produits_panier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produits_panier_panier ADD CONSTRAINT FK_921099CDF77D927C FOREIGN KEY (panier_id) REFERENCES panier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produits_panier_produit ADD CONSTRAINT FK_9F751D8E9F5F177C FOREIGN KEY (produits_panier_id) REFERENCES produits_panier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produits_panier_produit ADD CONSTRAINT FK_9F751D8EF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produits_panier DROP FOREIGN KEY FK_B5DD1611F77D927C');
        $this->addSql('DROP INDEX IDX_B5DD1611F77D927C ON produits_panier');
        $this->addSql('ALTER TABLE produits_panier DROP panier_id');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261016130658 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE baie (id INT AUTO_INCREMENT NOT NULL, numero VARCHAR(10) NOT NULL, nb_unites INT NOT NULL, UNIQUE INDEX UNIQ_709904BAF55AE19E (numero), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, date_creation DATE NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, periodicite VARCHAR(20) NOT NULL, prix_total NUMERIC(10, 2) NOT NULL, client_id INT DEFAULT NULL, offre_id INT DEFAULT NULL, INDEX IDX_6EEAA67D19EB6921 (client_id), INDEX IDX_6EEAA67D4CC8505A (offre_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE offre (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, nb_unites INT NOT NULL, prix_mensuel NUMERIC(8, 2) NOT NULL, remise_pourcent INT NOT NULL, actif TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE unite (id INT AUTO_INCREMENT NOT NULL, numero INT NOT NULL, etat VARCHAR(255) NOT NULL, nom VARCHAR(100) DEFAULT NULL, type VARCHAR(50) DEFAULT NULL, couleur VARCHAR(7) DEFAULT NULL, baie_id INT NOT NULL, commande_id INT DEFAULT NULL, INDEX IDX_1D64C11843375062 (baie_id), INDEX IDX_1D64C11882EA2E54 (commande_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, type VARCHAR(255) NOT NULL, entreprise VARCHAR(150) DEFAULT NULL, telephone VARCHAR(20) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id))');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D19EB6921 FOREIGN KEY (client_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D4CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id)');
        $this->addSql('ALTER TABLE unite ADD CONSTRAINT FK_1D64C11843375062 FOREIGN KEY (baie_id) REFERENCES baie (id)');
        $this->addSql('ALTER TABLE unite ADD CONSTRAINT FK_1D64C11882EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D19EB6921');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D4CC8505A');
        $this->addSql('ALTER TABLE unite DROP FOREIGN KEY FK_1D64C11843375062');
        $this->addSql('ALTER TABLE unite DROP FOREIGN KEY FK_1D64C11882EA2E54');
        $this->addSql('DROP TABLE baie');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE offre');
        $this->addSql('DROP TABLE unite');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

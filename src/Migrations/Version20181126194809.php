<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181126194809 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE car (id INT AUTO_INCREMENT NOT NULL, brand VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, numberplate VARCHAR(9) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE car_planning (id INT AUTO_INCREMENT NOT NULL, cars_id INT DEFAULT NULL, date_start DATETIME NOT NULL, am_pm_start VARCHAR(255) NOT NULL, date_end DATETIME NOT NULL, am_pm_end VARCHAR(255) NOT NULL, customer VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, user INT NOT NULL, INDEX IDX_5BAF8AF28702F506 (cars_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE car_planning ADD CONSTRAINT FK_5BAF8AF28702F506 FOREIGN KEY (cars_id) REFERENCES car (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE car_planning DROP FOREIGN KEY FK_5BAF8AF28702F506');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE car_planning');
    }
}

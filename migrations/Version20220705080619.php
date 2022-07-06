<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220705080619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE campervan (campervan_id VARCHAR(32) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(campervan_id))');
        $this->addSql('CREATE TABLE extra (extra_id VARCHAR(32) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(extra_id))');
        $this->addSql('CREATE TABLE rental_order (order_id VARCHAR(64) NOT NULL, start_date DATE NOT NULL --(DC2Type:date_immutable)
        , end_date DATE NOT NULL --(DC2Type:date_immutable)
        , start_station_id VARCHAR(32) NOT NULL, end_station_id VARCHAR(32) NOT NULL, campervan_id VARCHAR(32) NOT NULL, PRIMARY KEY(order_id))');
        $this->addSql('CREATE INDEX IDX_START_STATION_ID ON rental_order (start_station_id)');
        $this->addSql('CREATE INDEX IDX_END_STATION_ID ON rental_order (end_station_id)');
        $this->addSql('CREATE INDEX IDX_CAMPERVAN_ID ON rental_order (campervan_id)');
        $this->addSql('CREATE TABLE rental_order_extra (order_id VARCHAR(64) NOT NULL, extra_id VARCHAR(32) NOT NULL, quantity INTEGER NOT NULL, PRIMARY KEY(order_id, extra_id))');
        $this->addSql('CREATE INDEX IDX_ORDER_ID ON rental_order_extra (order_id)');
        $this->addSql('CREATE INDEX IDX_EXTRA_ID ON rental_order_extra (extra_id)');
        $this->addSql('CREATE TABLE station (station_id VARCHAR(32) NOT NULL, location VARCHAR(255) NOT NULL, PRIMARY KEY(station_id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE campervan');
        $this->addSql('DROP TABLE extra');
        $this->addSql('DROP TABLE rental_order');
        $this->addSql('DROP TABLE rental_order_extra');
        $this->addSql('DROP TABLE station');
    }
}

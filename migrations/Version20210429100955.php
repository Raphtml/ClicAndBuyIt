<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210429100955 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE advert_like DROP FOREIGN KEY FK_E54301FEA76ED395');
        $this->addSql('ALTER TABLE advert_like DROP FOREIGN KEY FK_E54301FED07ECCB6');
        $this->addSql('ALTER TABLE advert_like ADD CONSTRAINT FK_E54301FEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE advert_like ADD CONSTRAINT FK_E54301FED07ECCB6 FOREIGN KEY (advert_id) REFERENCES advert (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE advert_like DROP FOREIGN KEY FK_E54301FEA76ED395');
        $this->addSql('ALTER TABLE advert_like DROP FOREIGN KEY FK_E54301FED07ECCB6');
        $this->addSql('ALTER TABLE advert_like ADD CONSTRAINT FK_E54301FEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE advert_like ADD CONSTRAINT FK_E54301FED07ECCB6 FOREIGN KEY (advert_id) REFERENCES advert (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}

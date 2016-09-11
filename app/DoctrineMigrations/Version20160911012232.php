<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160911012232 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql("INSERT INTO company (name, field) values
            ('zoom car', 'travel'),
            ('practo', 'health'),
            ('sequoia', 'vc'),
            ('flipkart', 'ecommerce');");
        $this->addSql("INSERT into survey (company_id, question, type) values
            (1, 'Which type of car would you prefer for rentals?', 'multiple choice'),
            (1, 'How was your experience with car rentals? Rate from 1 to 5', 'rating'),
            (1, 'How much do you love to have Zoom car in your City? Rate from 1 to 5', 'rating'),
            (2, 'How was your experience with Practo Software? Rate from 1 to 5', 'rating'),
            (2, 'Which feature would be interested in Practo Ray?', 'multiple choice'),
            (3, 'How much funding does your start up need initially?', 'multiple choice'),
            (4, 'How was your experience with flipkart? Rate from 1 to 5', 'rating');");
        $this->addSql("INSERT into choice (survey_id, choice_name) values
            (1, 'Hatchback'),
            (1, 'Sedan'),
            (1, 'SUV'),
            (1, 'MPV'),
            (5, 'Templates'),
            (5, 'Patient Education'),
            (5, 'SMS Alerts'),
            (5, 'Email Alerts'),
            (6, '1-5 M $'),
            (6, '5-20 M $'),
            (6, '20-50 M $'),
            (6, '50-100 M $');");
        $this->addSql("INSERT  into user (age, phone, balance) values
            (20, '+919108169591', 20), (35, '+918005015642', 23),
            (20, '+919000000001', 20), (21, '+919000000002', 20),
            (22, '+919000000003', 20), (23, '+919000000004', 20),
            (24, '+919000000005', 20), (25, '+919000000006', 20),
            (26, '+919000000007', 20), (30, '+919000000008', 20),
            (40, '+919000000009', 20), (50, '+919000000010', 20);");
        $this->addSql("INSERT into user_survey (user_id, survey_id, choice_id, rating) values
            (1, 1, 1, null), (1, 2, null, 1), (1, 3, null, 5), (1, 4, null, 4),
            (1, 5, 5, null), (1, 6, 9, null), (1, 7, null, 5),
            (2, 1, 2, null), (2, 2, null, 2), (2, 3, null, 4), (2, 4, null, 5),
            (2, 5, 6, null), (2, 6, 11, null), (2, 7, null, 5),

            (3, 1, 1, null), (3, 2, null, 2), (3, 3, null, 4), (3, 4, null, 1),
            (3, 5, 7, null), (3, 6, 10, null), (3, 7, null, 1),
            (4, 1, 1, null), (4, 2, null, 4), (4, 3, null, 5), (2, 4, null, 4),
            (4, 5, 7, null), (4, 6, 11, null), (4, 7, null, 2),

            (5, 1, 1, null), (5, 2, null, 5), (5, 3, null, 2), (5, 4, null, 3),
            (5, 5, 7, null), (5, 6, 10, null), (5, 7, null, 3),
            (6, 1, 3, null), (6, 2, null, 1), (6, 3, null, 5), (6, 4, null, 2),
            (6, 5, 7, null), (6, 6, 11, null), (6, 7, null, 3),

            (7, 1, 1, null), (7, 2, null, 1), (7, 3, null, 1), (7, 4, null, 4),
            (7, 5, 8, null), (7, 6, 11, null), (7, 7, null, 5),
            (8, 1, 2, null), (8, 2, null, 3), (8, 3, null, 4), (8, 4, null, 5),
            (8, 5, 8, null), (8, 6, 11, null), (8, 7, null, 3),

            (9, 1, 1, null), (9, 2, null, 5), (9, 3, null, 3), (9, 4, null, 1),
            (9, 5, 5, null), (9, 6, 9, null), (9, 7, null, 5),
            (10, 1, 1, null), (10, 2, null, 5), (10, 3, null, 4), (10, 4, null, 3),
            (10, 5, 5, null), (10, 6, 12, null), (10, 7, null, 3),

            (11, 1, 3, null), (11, 2, null, 5), (11, 3, null, 4), (11, 4, null, 3),
            (11, 5, 7, null), (11, 6, 12, null), (11, 7, null, 5),
            (12, 1, 4, null), (12, 2, null, 5), (12, 3, null, 4), (12, 4, null, 4),
            (12, 5, 7, null), (12, 6, 9, null), (12, 7, null, 4)

        ;");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('SET FOREIGN_KEY_CHECKS = 0');
        $this->addSql('truncate company');
        $this->addSql('truncate survey');
        $this->addSql('truncate choice');
        $this->addSql('truncate user');
        $this->addSql('truncate user_survey');
    }
}

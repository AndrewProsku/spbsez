<?php


use Phinx\Migration\AbstractMigration;

class AddEngQuestions extends AbstractMigration
{
    public function change()
    {
       $this->execute('ALTER TABLE kelnik_questions ADD LANG CHAR(2) NULL');
       $this->execute('INSERT INTO kelnik_questions(SORT, ACTIVE, NAME, URL, TEXT_TEXT_TYPE, LANG) VALUES(500, "Y", "Become a resident", "https://www.spbsez.ru/en/investors/#investors-resident", "html", "en")');
       $this->execute('INSERT INTO kelnik_questions(SORT, ACTIVE, NAME, URL, TEXT_TEXT_TYPE, LANG) VALUES(500, "Y", "Data Center Services", "https://www.spbsez.ru/en/services/#data-services-center", "html", "en")');
       $this->execute('INSERT INTO kelnik_questions(SORT, ACTIVE, NAME, URL, TEXT_TEXT_TYPE, LANG) VALUES(500, "Y", "Rental of meeting rooms", "https://www.spbsez.ru/en/services/#rental-of-meeting-rooms", "html", "en")');
       $this->execute('INSERT INTO kelnik_questions(SORT, ACTIVE, NAME, URL, TEXT_TEXT_TYPE, LANG) VALUES(500, "Y", "Tariffs for the services of the Management Company", "https://www.spbsez.ru/en/services/", "html", "en")');
       $this->execute('INSERT INTO kelnik_questions(SORT, ACTIVE, NAME, URL, TEXT_TEXT_TYPE, LANG) VALUES(500, "Y", "Benefits and preferences", "https://www.spbsez.ru/en/#benefits-and-preferences", "html", "en")');
    }

}


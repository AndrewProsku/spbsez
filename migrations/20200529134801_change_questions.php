<?php


use Phinx\Migration\AbstractMigration;

class ChangeQuestions extends AbstractMigration
{
    public function change()
    {
       $this->execute('UPDATE kelnik_questions SET URL = "https://www.spbsez.ru/services/#data-services-center" WHERE ID = 2');
       $this->execute('UPDATE kelnik_questions SET URL = "https://www.spbsez.ru/services/#rental-of-meeting-rooms" WHERE ID = 3');
       $this->execute('UPDATE kelnik_questions SET URL = "https://www.spbsez.ru/#benefits-and-preferences" WHERE ID = 5');
    }

}


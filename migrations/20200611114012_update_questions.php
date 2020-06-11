<?php

use Phinx\Migration\AbstractMigration;

class UpdateQuestions extends AbstractMigration
{
    public function change()
    {
        $this->execute("UPDATE kelnik_questions SET LANG = 'ru' WHERE ID < 6;");
        $this->execute("UPDATE kelnik_questions SET LANG = 'en' WHERE ID > 5 AND ID < 11;");
    }
}


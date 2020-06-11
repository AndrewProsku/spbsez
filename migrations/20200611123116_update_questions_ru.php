<?php

use Phinx\Migration\AbstractMigration;

class UpdateQuestionsRu extends AbstractMigration
{
    public function change()
    {
        $this->execute("UPDATE kelnik_questions SET LANG = 'ru' WHERE ID = 1;");
        $this->execute("UPDATE kelnik_questions SET LANG = 'ru' WHERE ID = 2;");
        $this->execute("UPDATE kelnik_questions SET LANG = 'ru' WHERE ID = 3;");
        $this->execute("UPDATE kelnik_questions SET LANG = 'ru' WHERE ID = 4;");
        $this->execute("UPDATE kelnik_questions SET LANG = 'ru' WHERE ID = 5;");
    }
}


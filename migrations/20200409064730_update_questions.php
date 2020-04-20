<?php


use Phinx\Migration\AbstractMigration;

class UpdateQuestions extends AbstractMigration
{
    public function change()
    {
        $this->table('kelnik_questions', ['id' => 'ID'])
            ->changeColumn(
                'ACTIVE',
                'char',
                [
                    'limit' => 1,
                    'default' => 'N',
                    'null' => false,
                ]
            )
            ->update();
    }
}

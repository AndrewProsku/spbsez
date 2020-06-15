<?php

use Phinx\Migration\AbstractMigration;

class AddFieldsForNews extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('kelnik_news');

        if (!$table->hasColumn('LANG')) {
            $table->addColumn('LANG', 'string', ['limit' => 2, 'default' => 'ru'])->update();
        }
    }
}


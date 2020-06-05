<?php


use Phinx\Migration\AbstractMigration;

class UpdateReport extends AbstractMigration
{
    public function change()
    {
        $this->execute('UPDATE kelnik_report SET STATUS_ID = 2 WHERE ID = 88;');
    }
}


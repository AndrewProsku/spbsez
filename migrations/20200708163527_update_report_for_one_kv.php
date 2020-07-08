<?php


use Phinx\Migration\AbstractMigration;

class UpdateReportForOneKv extends AbstractMigration
{
    public function change()
    {
        $this->execute('UPDATE kelnik_report SET STATUS_ID = 1 WHERE ID = 122;');
    }
}

<?php


use Phinx\Migration\AbstractMigration;

class UpdateReportForTreeKv extends AbstractMigration
{
    public function change()
    {
        $this->execute('UPDATE kelnik_report SET STATUS_ID = 1 WHERE ID = 63;');
    }
}

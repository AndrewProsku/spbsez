<?php

use Phinx\Migration\AbstractMigration;

class UpdateReportForFarmaHolding extends AbstractMigration
{
    public function change()
    {
        $this->execute('UPDATE kelnik_report SET IS_LOCKED = "Y" WHERE ID = 161;');
        $this->execute('UPDATE kelnik_report SET IS_LOCKED = "Y" WHERE ID = 88;');
    }
}

<?php

use Phinx\Migration\AbstractMigration;

class AddRecordsInTableFieldsReport extends AbstractMigration
{
    public function change()
    {
        $this->execute("INSERT INTO kelnik_report_fields(REPORT_ID, NAME) VALUES(50, 'revenue-all-extra')");
        $this->execute("INSERT INTO kelnik_report_fields(REPORT_ID, NAME) VALUES(50, 'revenue-year-extra')");
    }
}


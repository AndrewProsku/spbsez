<?php


use Phinx\Migration\AbstractMigration;

class MultisitePressContact extends AbstractMigration
{
    public function change()
    {
        $this->table('kelnik_multisites')
            ->addColumn(
                'PRESS_CONTACT',
                'text',
                [
                    'default' => null,
                    'null' => true
                ]
            )
            ->update();
    }
}

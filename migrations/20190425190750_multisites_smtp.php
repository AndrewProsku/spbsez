<?php


use Phinx\Migration\AbstractMigration;

class MultisitesSmtp extends AbstractMigration
{
    public function change()
    {
        $this->table('kelnik_multisites')
            ->addColumn(
                'USE_SMTP',
                'enum',
                [
                    'values' => ['Y', 'N'],
                    'default' => 'N',
                    'null' => false,
                    'after' => 'ACTIVE'
                ]
            )
            ->addColumn(
                'SMTP_HOST',
                'string',
                [
                    'default' => null,
                    'null' => true,
                    'after' => 'TEMPLATE_ID',
                    'limit' => 255
                ]
            )
            ->addColumn(
                'SMTP_USER',
                'string',
                [
                    'default' => null,
                    'null' => true,
                    'after' => 'SMTP_HOST',
                    'limit' => 255
                ]
            )
            ->addColumn(
                'SMTP_PWD',
                'string',
                [
                    'default' => null,
                    'null' => true,
                    'after' => 'SMTP_USER',
                    'limit' => 255
                ]
            )
            ->update();
    }
}

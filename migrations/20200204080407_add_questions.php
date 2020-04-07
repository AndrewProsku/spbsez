<?php


use Phinx\Migration\AbstractMigration;

class AddQuestions extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->table('kelnik_questions', ['id' => 'ID'])
            ->addColumn('SORT', 'integer')
            ->addColumn(
                'ACTIVE',
                'enum',
                [
                    'values' => ['Y', 'N'],
                    'after' => 'SORT',
                    'default' => 'N'
                ]
            )
            ->addColumn('NAME', 'string')
            ->addColumn('URL', 'string')
            ->addColumn('TEXT', 'text', ['null' => true])
            ->addColumn('TEXT_TEXT_TYPE', 'char',['limit' => 4])
            ->create();
    }
}

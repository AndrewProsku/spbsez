<?php


use Phinx\Migration\AbstractMigration;

class MultiSitesFields extends AbstractMigration
{
    public function change()
    {
        $this->table('kelnik_multisites')
            ->addColumn(
                'SOCIAL_INST',
                'string',
                [
                    'limit' => 255,
                    'after' => 'ADDRESS',
                    'default' => null,
                    'null' => true
                ]
            )
            ->addColumn(
                'SOCIAL_FACEBOOK',
                'string',
                [
                    'limit' => 255,
                    'after' => 'SOCIAL_INST',
                    'default' => null,
                    'null' => true
                ]
            )
            ->addColumn(
                'MAIN_VIDEO_MP4',
                'integer',
                [
                    'after' => 'ID',
                    'default' => 0,
                    'null' => true
                ]
            )
            ->addColumn(
                'MAIN_VIDEO_OGV',
                'integer',
                [
                    'after' => 'MAIN_VIDEO_MP4',
                    'default' => 0,
                    'null' => true
                ]
            )
            ->addColumn(
                'MAIN_VIDEO_WEBM',
                'integer',
                [
                    'after' => 'MAIN_VIDEO_OGV',
                    'default' => 0,
                    'null' => true
                ]
            )
            ->update();
    }
}

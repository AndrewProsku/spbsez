<?php


use Phinx\Migration\AbstractMigration;

class MultiSitesSocialYouTube extends AbstractMigration
{
    public function change()
    {
        $this->table('kelnik_multisites')
            ->addColumn(
                'SOCIAL_YOUTUBE',
                'string',
                [
                    'limit' => 255,
                    'after' => 'SOCIAL_FACEBOOK',
                    'default' => null,
                    'null' => true
                ]
            )
            ->update();
    }
}

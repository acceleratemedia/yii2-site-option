<?php

namespace bvb\siteoption\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `site_option`.
 */
class m200723_102106_create_site_option_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%site_option}}', [
            'key' => $this->string(50),
            'value' => 'LONGTEXT NOT NULL',
            'create_time' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'update_time' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE CURRENT_TIMESTAMP'),
            'PRIMARY KEY(`key`)',
        ]);


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%site_option}}');
    }
}

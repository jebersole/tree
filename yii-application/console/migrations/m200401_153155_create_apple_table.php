<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%apple}}`.
 */
class m200401_153155_create_apple_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%apple}}', [
            'id' => $this->primaryKey(),
            'color' => $this->string()->notNull()->defaultValue(''),
            'created_at' => $this->integer()->notNull()->unsigned()->defaultValue(0),
            'fallen_at' => $this->integer()->unsigned()->defaultValue(0),
            'status' => $this->tinyInteger()->notNull()->unsigned()->defaultValue(0),
            'percent_eaten' => $this->tinyInteger()->notNull()->unsigned()->defaultValue(0),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%apple}}');
    }
}

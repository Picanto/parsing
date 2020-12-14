<?php

use yii\db\Migration;

/**
 * Class m201214_163350_topic_table
 */
class m201214_163350_topic_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('topic', [
            'id' => $this->primaryKey(),
            'title' => $this->string(), // Название статьи
            'href' => $this->string(),  // Ссылка  на статью
            'text' => $this->text(),    // Детальный текст статьи
            'date' => $this->string(),  // Дата публикации статьи
            'image' => $this->string()  // Сохранить в папку картинку (превью)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('topic');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201214_163350_topic_table cannot be reverted.\n";

        return false;
    }
    */
}

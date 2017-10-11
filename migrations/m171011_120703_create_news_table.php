<?php

use yii\db\Migration;

/**
 * Handles the creation of table `news`.
 */
class m171011_120703_create_news_table extends Migration {
    /**
     * @inheritdoc
     */
    public function up() {
        $this->createTable('news', [
            'id' => $this->primaryKey()->unsigned(),
	        'title' => $this->string(255)->notNull(),
	        'about' => $this->text()->notNull(),
	        'text' => $this->text()->notNull(),
	        'user_id' => $this->integer(10)->unsigned(),
	        'date_creation' => $this->timestamp(),
	        'date_modification' => $this->timestamp()
        ]);

	    $this->createIndex(
		    'user_id',
		    'news',
		    'user_id'
	    );

	    $this->addForeignKey(
		    'fk-news-user_id',
		    'news',
		    'user_id',
		    'user',
		    'id',
		    'RESTRICT',
		    'CASCADE'
	    );
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropTable('news');
    }

}

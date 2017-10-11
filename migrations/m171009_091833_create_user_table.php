<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m171009_091833_create_user_table extends Migration {
    /**
     * @inheritdoc
     */
    public function up() {
        $this->createTable('user', [
            'id' => $this->primaryKey()->unsigned(),
	        'name' => $this->string(32)->notNull(),
	        'email' => $this->string(64)->notNull()->unique(),
	        'password' => $this->binary(60)->notNull(),
	        'hash' => $this->string(64),
	        'type' => $this->smallInteger(1),
	        'status' => $this->smallInteger(1),
	        'date_creation' => $this->timestamp(),
	        'date_modification' => $this->timestamp()
        ]);

	    $bcrypt = new \app\components\Bcrypt();
        $this->insert('user', [
        	'name' => 'Админ',
	        'email' => 'admin',
	        'password' => $bcrypt->hash('admin'),
	        'hash' => md5(time()),
	        'type' => \app\models\User::TYPE_ADMIN,
	        'status' => \app\models\User::STATUS_ACTIVE,
	        'date_creation' => new \yii\db\Expression('NOW()')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropTable('user');
    }

}

<?php

class m140322_165221_create_eyelogbook_account_table extends CDbMigration
{
	public function up()
	{
		$this->createTable('eyelogbook_account', array(
			'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
			'user_id' => 'int(10) unsigned NOT NULL',
			'username' => 'varchar(20) NOT NULL',
			'password' => 'varchar(40) DEFAULT NULL',
			'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
			'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
			'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
			'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
			'PRIMARY KEY (`id`)',
			'KEY `eyelogbook_account_user_id_fk` (`user_id`)',
			'KEY `eyelogbook_account_last_modified_user_id_fk` (`last_modified_user_id`)',
			'KEY `eyelogbook_account_created_user_id_fk` (`created_user_id`)',
			'CONSTRAINT `eyelogbook_account_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)',
			'CONSTRAINT `eyelogbook_account_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
			'CONSTRAINT `eyelogbook_account_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
		), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');
	}

	public function down()
	{
		$this->dropTable('eyelogbook_account');
		return true;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}
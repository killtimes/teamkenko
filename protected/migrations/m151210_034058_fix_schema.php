<?php

class m151210_034058_fix_schema extends CDbMigration
{
	public function up()
	{
		$this->execute("
ALTER TABLE `Session` CHANGE `user_id` `user_id` INT(11) NULL;
ALTER TABLE `Session` CHANGE `last_ip` `last_ip` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
		");
	}

	public function down()
	{
		echo "m151210_034058_fix_schema does not support migration down.\n";
		return false;
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
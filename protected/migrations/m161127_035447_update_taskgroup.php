<?php

class m161127_035447_update_taskgroup extends CDbMigration
{
	public function up()
	{
	    $this->execute("
	    ALTER TABLE `Task` ADD INDEX(`task_group`);
	    ");
	}

	public function down()
	{
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
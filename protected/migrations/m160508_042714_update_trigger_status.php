<?php

class m160508_042714_update_trigger_status extends CDbMigration
{
	public function up()
	{
		$this->execute("
		DROP TRIGGER IF EXISTS `update_status_task`;
		CREATE DEFINER=`root`@`localhost` TRIGGER `update_status_task` AFTER UPDATE ON `Process` FOR EACH ROW UPDATE `TaskProcess` SET `status` = NEW.`status` WHERE `process_id` = NEW.id and NEW.`status`=-1;
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
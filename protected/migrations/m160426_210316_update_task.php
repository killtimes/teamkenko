<?php

class m160426_210316_update_task extends CDbMigration
{
	public function up()
	{
		$this->execute("
		ALTER TABLE `TaskProcessTemplate` ADD `is_att_mandatory` BOOLEAN NOT NULL DEFAULT FALSE;
		ALTER TABLE `TaskProcessTemplate` ADD `can_not_reject` BOOLEAN NOT NULL DEFAULT FALSE;
		ALTER TABLE `TaskProcess` ADD `is_att_mandatory` BOOLEAN NOT NULL DEFAULT FALSE;
		ALTER TABLE `TaskProcess` ADD `can_not_reject` BOOLEAN NOT NULL DEFAULT FALSE;
		");
	}

	public function down()
	{
		$this->execute("
alter table TaskProcessTemplate drop COLUMN is_att_mandatory;
alter table TaskProcessTemplate drop COLUMN can_not_reject;
alter table TaskProcess drop COLUMN is_att_mandatory;
alter table TaskProcess drop COLUMN can_not_reject;
");
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
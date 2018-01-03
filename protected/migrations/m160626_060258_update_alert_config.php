<?php

class m160626_060258_update_alert_config extends CDbMigration
{
	public function up()
	{
		$this->execute("
		ALTER TABLE `TaskProcessTemplate` ADD `send_mail_recipient_extra` VARCHAR(255) NULL;
		ALTER TABLE `TaskProcess`  ADD `send_mail_recipient_extra` VARCHAR(255) NULL;
		ALTER TABLE `AlertRecipientConfig` ADD `send_mail` BOOLEAN NOT NULL DEFAULT FALSE;
		ALTER TABLE `AlertRecipientConfigTemplate` ADD `send_mail` BOOLEAN NOT NULL DEFAULT FALSE;
		ALTER TABLE `Alert` CHANGE `to_user_id` `to_user_id` INT(11) NULL;
		");
	}

	public function down()
	{
		$this->execute("
		Alter table TaskProcessTemplate drop COLUMN send_mail_recipient_extra;
		Alter table TaskProcess  drop COLUMN send_mail_recipient_extra;
		ALTER TABLE `AlertRecipientConfig` drop COLUMN `send_mail`;
		ALTER TABLE `AlertRecipientConfigTemplate` drop COLUMN `send_mail`;
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
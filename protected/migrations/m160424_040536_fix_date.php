<?php

class m160424_040536_fix_date extends CDbMigration
{
    public function up()
    {
        $this->execute("
        ALTER TABLE `Process`
	CHANGE `start_date` `start_date` TIMESTAMP NULL,
	CHANGE `update_date` `update_date` TIMESTAMP NULL;

	ALTER TABLE `TaskProcess`
	CHANGE `update_date` `update_date` TIMESTAMP NULL;
        ");

        $this->execute("
		ALTER TABLE `TaskProcess`
 	ADD `alert_condition` INT NOT NULL DEFAULT '0',
 	ADD `alert_enable` TINYINT NOT NULL DEFAULT '0',
 	ADD `alert_recipient` INT NULL;
		");

        $this->execute("
        CREATE TABLE `AlertRecipientConfig` (
  `task_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `AlertRecipientConfig`
  ADD PRIMARY KEY (`task_id`,`user_id`),
  ADD KEY `alert_recipient_config_fk3` (`user_id`);

ALTER TABLE `AlertRecipientConfig`
  ADD CONSTRAINT `alert_recipient_config_fk4` FOREIGN KEY (`task_id`) REFERENCES `TaskProcess` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `alert_recipient_config_fk5` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`);

CREATE TABLE `AlertUser` ( `alert_id` INT NOT NULL , `user_id` INT NOT NULL ) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE `AlertUser` ADD PRIMARY KEY( `alert_id`, `user_id`);

ALTER TABLE `Alert` CHANGE `to_user_id` `to_user_id` INT(11) NULL;
        ");

    }

    public function down()
    {
        $this->execute("
		drop table if EXISTS AlertRecipientConfig;
		ALTER table TaskProcess drop COLUMN alert_condition, DROP  COLUMN  alert_enable, drop COLUMN  alert_recipient;
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
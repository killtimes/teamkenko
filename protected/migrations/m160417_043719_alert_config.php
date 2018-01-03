<?php

class m160417_043719_alert_config extends CDbMigration
{
    public function up()
    {

        $this->execute("
ALTER TABLE `TaskProcessTemplate` CHANGE `update_date` `update_date` TIMESTAMP NULL DEFAULT NULL;
ALTER TABLE `TaskProcessTemplate` ADD `alert_condition` INT NOT NULL DEFAULT '0';
ALTER TABLE `TaskProcessTemplate` ADD `alert_enable` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `TaskProcessTemplate` ADD `alert_recipient` INT NULL;


CREATE TABLE `AlertRecipientConfigTemplate` (
  `task_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `AlertRecipientConfigTemplate`
  ADD PRIMARY KEY (`task_id`,`user_id`),
  ADD KEY `alert_recipient_config_fk2` (`user_id`);

ALTER TABLE `AlertRecipientConfigTemplate`
  ADD CONSTRAINT `alert_recipient_config_fk1` FOREIGN KEY (`task_id`) REFERENCES `TaskProcessTemplate` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `alert_recipient_config_fk2` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`);
");
    }

    public function down()
    {
        $this->execute("
ALTER TABLE `TaskProcessTemplate` DROP COLUMN `alert_condition`, DROP COLUMN `alert_enable`,  DROP COLUMN `alert_recipient`;
drop table if EXISTS AlertRecipientConfigTemplate;

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
<?php

class m160316_011209_alert extends CDbMigration
{
	public function up()
	{
		$this->execute("
CREATE TABLE `Alert` (
  `id` int(11) NOT NULL,
  `alert_type` tinyint(4) NOT NULL,
  `note` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` timestamp NULL DEFAULT NULL,
  `create_by` int(11) NOT NULL,
  `update_by` int(11) DEFAULT NULL,
  `to_user_id` int(11) NOT NULL,
  `stage` tinyint(4) NOT NULL,
  `related_task_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `Alert`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `Alert`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
		");
	}

	public function down()
	{
		$this->execute("
drop table if EXISTS `Alert`;
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
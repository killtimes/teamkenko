<?php

class m160227_105953_table_usershop extends CDbMigration
{
    public function up()
    {
        $this->execute("
CREATE TABLE `UserShop` (
  `user_id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `UserShop`
  ADD PRIMARY KEY (`user_id`,`shop_id`),
  ADD KEY `fk2` (`shop_id`);

ALTER TABLE `UserShop`
  ADD CONSTRAINT `fk1` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`),
  ADD CONSTRAINT `fk2` FOREIGN KEY (`shop_id`) REFERENCES `Shop` (`id`);


		");

    }

    public function down()
    {
        $this->execute("
        DROP TABLE IF EXISTS `UserShop`;
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
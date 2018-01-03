<?php

class m151122_145120_add_tz_col extends CDbMigration {

    public function up() {
        $this->execute("ALTER TABLE  `User` ADD  `tz` VARCHAR( 30 ) NULL DEFAULT NULL AFTER  `last_activity` ;");
        $this->execute("ALTER TABLE  `TaskActivity` CHANGE  `action_object`  `action_object` VARCHAR( 255 ) NULL DEFAULT NULL ;");
        $this->execute("ALTER TABLE  `Task` ADD  `status` TINYINT NOT NULL DEFAULT  '1' AFTER  `instructions` ;");
    }

    public function down() {
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

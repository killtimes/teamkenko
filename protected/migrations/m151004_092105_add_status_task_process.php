<?php

class m151004_092105_add_status_task_process extends CDbMigration {

    public function safeUp() {
        $this->execute("ALTER TABLE  `TaskProcess` ADD  `status` TINYINT NOT NULL DEFAULT 1 AFTER  `due_date` ;");
        $this->execute("
            CREATE DEFINER =  `root`@`localhost` TRIGGER `update_status_task` AFTER UPDATE ON  `Process` FOR EACH ROW UPDATE `TaskProcess` SET `status` = NEW.`status` WHERE `process_id` = NEW.id
            ");
    }

    public function safeDown() {
        $this->execute("ALTER TABLE  `TaskProcess` DROP  `status` ;"
                . "DROP TRIGGER IF EXISTS  `update_status_task` ;");
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

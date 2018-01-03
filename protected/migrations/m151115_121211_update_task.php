<?php

class m151115_121211_update_task extends CDbMigration {

    public function up() {
        $this->execute("UPDATE  `taskman`.`Task` SET  `name` =  'Paperwork return*' WHERE  `Task`.`id` =163;");
        $this->execute("ALTER TABLE  `Task` ADD UNIQUE (`name`);");
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

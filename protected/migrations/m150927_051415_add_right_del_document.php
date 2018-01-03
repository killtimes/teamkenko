<?php

class m150927_051415_add_right_del_document extends CDbMigration {

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp() {
        //add column upload by
        $this->execute("ALTER TABLE `Document` ADD `upload_by` INT NOT NULL AFTER `update_date` ;");
        //add column status
        $this->execute("ALTER TABLE `Document` ADD `status` TINYINT( 1 ) NOT NULL DEFAULT '1' AFTER `upload_by` ;");
        //add column status
        $this->execute("ALTER TABLE `TaskActivity` ADD `status` INT NOT NULL DEFAULT '1' AFTER `action_date` ;");

        $this->execute("update `Document` set `status`=1;");
        $this->execute("UPDATE  `Document` d INNER JOIN TaskActivity ta ON d.task_id = ta.task_id SET d.upload_by = ta.action_source WHERE d.upload_by =0");
        $this->execute("update `TaskActivity` set `status`=1;");

        // add 2 new operation
        $this->insert('AuthItem', array(
            'name' => 'Document_DeleteOwn',
            'type' => 0,
            'description' => '[Document] Delete own document',
            'bizrule' => null,
            'data' => 'N;'
        ));
        $this->insert('AuthItem', array(
            'name' => 'Document_DeleteAny',
            'type' => 0,
            'description' => '[Document] Delete any document',
            'bizrule' => null,
            'data' => 'N;'
        ));

        $this->insert('AuthItem', array(
            'name' => 'Message_DeleteOwn',
            'type' => 0,
            'description' => '[Message] Delete own message',
            'bizrule' => null,
            'data' => 'N;'
        ));
        $this->insert('AuthItem', array(
            'name' => 'Message_DeleteAny',
            'type' => 0,
            'description' => '[Message] Delete any message',
            'bizrule' => null,
            'data' => 'N;'
        ));

        //update super email
        $this->execute(" update `User` set email='support@teamkenko.com' where username='super'");

        //new user system
        $this->insert('User', array(
            'username' => 'system',
            'password' => '8d68c593a064145397488d41a334ca53',
            'email' => 'cs.amateur@gmail.com',
            'activkey' => '4f0c54006f74beebd20877b4d7fe02cc',
            'create_at' => '2015-09-27 06:59:59',
            'superuser' => 1,
            'status' => 1,
            'last_ip' => '',
            'last_activity' => null
        ));
    }

    public function safeDown() {
        echo "m150927_051415_add_right_del_document does not support migration down.\n";
        return false;
    }

}

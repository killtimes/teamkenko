<?php

class m150927_112410_add_system_contact extends CDbMigration {

    public function safeUp() {
        $this->insert('Profile', array(
            'user_id' => 109,
            'lastname' => 'Admin',
            'firstname' => 'System',
            'department' => 4,
            'mobile_phone' => '',
            'address' => 'HCMC, VN',
            'shop_id' => null
        ));
    }

    public function safeDown() {
        echo "m150927_112410_add_system_contact does not support migration down.\n";
        return false;
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

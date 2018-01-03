<?php

class m160707_111207_fix_alert extends CDbMigration
{

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        $rows = $this->getDbConnection()->createCommand()
            ->select('id,alert_recipient')
            ->from('TaskProcess')
            ->where('alert_recipient is not null')
            ->queryAll();

        $insert = array();
        foreach ($rows as $r) {
            $insert[] = array(
                'task_id' => $r['id'],
                'user_id' => $r['alert_recipient'],
                'send_mail' => 0
            );
        }

        if(count($insert) > 0){
            $this->getDbConnection()->getCommandBuilder()->createMultipleInsertCommand('AlertRecipientConfig', $insert)->execute();

            $this->execute('update TaskProcess set alert_recipient=NULL where alert_recipient is not NULL; ');
        }


    }

    public function safeDown()
    {
    }

}
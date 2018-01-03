<?php

class m160529_125826_update_task extends CDbMigration
{
    public function up()
    {
        $this->execute('
        ALTER TABLE `TaskProcessTemplate` ADD `task_type` tinyint NOT NULL DEFAULT 1;
        ALTER TABLE `TaskProcess` ADD `task_type` tinyint NOT NULL DEFAULT 1;
        ');
    }

    public function down()
    {
        $this->execute('
        alter table TaskProcessTemplate drop COLUMN task_type;
        alter table TaskProcess drop COLUMN task_type;
        ');
    }

}
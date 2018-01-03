<?php

class FixOrderCommand extends CConsoleCommand {

    public function run($args) {

        Yii::beginProfile('test');
        Yii::import('application.modules.process.models.TaskProcess');
        Yii::import('application.modules.process.models.Process');

        $processes = Process::model()->findAll();

        foreach ($processes as $process) {
            echo "\nfix process: {$process->id}";
            $tasks = $process->taskProcesses;

            $w = '';
            $order = 1;
            $old_order = '';
            $new_order = '';
            foreach ($tasks as $t) {
                $old_order .= $t->sort_order . ',';
                $new_order .= $order . ',';
                $w.=' WHEN ' . $t->id . ' THEN ' . $order;
                $order++;
            }
            echo "\nold order: $old_order";
            echo "\nold order: $new_order";

            $sql = 'UPDATE `TaskProcess` SET `sort_order`= CASE `id` ';
            $sql.= $w;
            $sql.=' END ';
            $sql.=' WHERE `process_id`=' . $process->id;

            $result = Yii::app()->db->createCommand($sql)->execute();
            echo "\n executed: $result";
        }

        Yii::endProfile('test');
        $logger = Yii::getLogger();

        exit;
    }

    public function slugify($str) {
        $str = preg_replace('/[^A-Za-z0-9]/', ' ', $str);
        $str = preg_replace('/\s+/', ' ', $str);
        $str = str_replace(' ', '-', trim($str));
        return mb_strtoupper($str);
    }

}

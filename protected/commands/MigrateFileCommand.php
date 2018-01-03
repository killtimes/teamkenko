<?php

class MigrateFileCommand extends CConsoleCommand {

    public function run($args) {

        Yii::beginProfile('test');
        Yii::import('application.modules.process.models.Document');
        Yii::import('application.modules.process.models.TaskProcess');
        Yii::import('application.modules.process.models.Process');

        $documents = Document::model()->findAll();

        foreach ($documents as $doc) {

            //set shop, contact
            $task = TaskProcess::model()->findByPk($doc->task_id);

            $process = $task->process;

            if ($process === null) {
                $doc->shop_id = $task->shop_id;
                $doc->supplier_id = $task->supplier_id;
            } else {
                $doc->shop_id = $process->shop_id;
                $doc->supplier_id = $process->supplier_id;
            }

            if (empty($doc->shop_id)) {
                $doc->shop_id = 0;
            }

            if (empty($doc->supplier_id)) {
                $doc->supplier_id = 0;
            }

            $oldfilename = $doc->file_name;

            $oldfilePath = Yii::getPathOfAlias('consolesource') . DIRECTORY_SEPARATOR . $oldfilename;

            if (is_file($oldfilePath)) {

                $ext = end(explode('.', $doc->title));

                $newTempName = str_replace('.' . $ext, '', $doc->title);

                $newfilename = microtime(true) . '_' . self::slugify($newTempName) . '.' . $ext;

                $newRootPath = Yii::getPathOfAlias('consoletarget');

                $newtempPath = sprintf("%s%s%s%s", DIRECTORY_SEPARATOR, $doc->shop_id, DIRECTORY_SEPARATOR, $doc->supplier_id);
                $newRelativePath = $newRootPath . $newtempPath;

                if (!is_dir($newRelativePath) && !mkdir($newRelativePath, 0774, true)) {
                    throw new CException('Cant make directory');
                }

//                chown($newRelativePath, 'nginx');
//                chgrp($newRelativePath, 'nginx');
//                chmod($newRelativePath, 0774);

                $newFilePath = $newRelativePath . DIRECTORY_SEPARATOR . $newfilename;
                echo "\ncopy " . $oldfilePath;
                echo "\nto " . $newFilePath;
                if (copy($oldfilePath, $newFilePath)) {

//                    chown($newFilePath, 'nginx');
//                    chgrp($newFilePath, 'nginx');
//                    chmod($newFilePath, 0774);

                    echo "\ncopy ok";
                    //update db
                    $doc->file_name = $newtempPath . DIRECTORY_SEPARATOR . $newfilename;

                    $doc->file_name;

                    if ($doc->save(false, array('shop_id', 'supplier_id', 'file_name'))) {
                        echo "\nupdate doc ok";
                    } else {
                        echo "\nupdate failed: " . CVarDumper::dumpAsString($doc->errors);
                    }
                } else {
                    echo "\n copy failed\n";
                }

                system("/bin/chown -R nginx:nginx " . escapeshellarg($newRootPath));
                system("/bin/chmod -R 0774 " . escapeshellarg($newRootPath));
            } else {
                echo "\n" . $oldfilePath;
                echo "\nnot found";
            }
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

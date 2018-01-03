<?php

class AttachmentController extends RController {

    public $defaultAction = 'view';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

//    public function actionDownload($id) {
//        $model = Document::model()->findByPk($id);
//
//        if ($model === null) {
//            $this->notfoundException();
//        }
//
//        //get path
//        if ($model->source_type == Document::SOURCE_TYPE_LOCAL) {
//
//            $path = $model->getFilePath();
//
//            if (is_file($path)) {
//                
//            }
//        }
//    }

    public function actionView($id) {        

        $model = Document::model()->findByPk($id);

        if ($model === null) {
            $this->notfoundException();
        }

        //get image path
        if ($model->source_type == Document::SOURCE_TYPE_LOCAL) {

            $path = $model->getFilePath();

            if (is_file($path)) {

//                $data = getimagesize($path);

                $expires = 60 * 60 * 30; //1 hour
                //
                //not image
//                if (!$data) {

                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime = finfo_file($finfo, $path);
                    finfo_close($finfo);

                    $ext = pathinfo($path, PATHINFO_EXTENSION);

                    header("Content-Type: $mime");
                    header('Content-disposition: inline; filename="' . $model->title . '.' . $ext . '"');
//                } else {
//                    header('Content-type: ' . $data['mime']);
//                }
                header("Pragma: public");
                header('Content-length: ' . filesize($path));
                header("Cache-Control: maxage=" . $expires);
                header('Expires: ' . gmdate("D, d M Y H:i:s", ($expires + time())));
//                ob_clean();
//                flush();
//                ob_end_flush();
                readfile($path);
            } else {
                $this->notfoundException();
            }
        } else {
            //dropbox
            $this->redirect($model->file_name);
        }
        
        exit;
    }

}

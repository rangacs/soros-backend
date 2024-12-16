<?php

class ApClientController extends BaseController {

//    	public $layout='//layouts/column';

    public function init() {
        $pathinfo = pathinfo(Yii::app()->request->scriptFile);
        $uploaddir = $pathinfo['dirname'] . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR;
        require($uploaddir . 'defines.php');
    }

    public function actionIndex() {

        $this->render('tag-dash');
    }


}

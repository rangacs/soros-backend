<?php

class SiteSettingsController extends BaseController {

    public function actionIndex() {
        $res = array(9);
        $setPoints = SetPoints::model()->findAll();
        $dataBoundries = DataBoundries::model()->findAll();
        // $tagView = LayoutWidgets::model()->find('widget_id=569');
        $this->sendSuccessResponse(array('data' => array('setPoints' => $setPoints,
         'dataBoundries' => $dataBoundries)));

	
    }

}

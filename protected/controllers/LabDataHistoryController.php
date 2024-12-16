<?php

/**
 * TagCompletedController implements the CRUD actions for TagCompleted model.
 */
class LabDataHistoryController extends BaseController
{
 public function actionIndex()
    {
//        sleep(60);
        $params = $this->request['search'];
        $searchModel = new LabDataHistorySearch();
        $response = $searchModel->search($params);
        Yii::$app->api->sendSuccessResponse($response['data'], $response['info']);
    }

    public function actionCreate()
    {

        $model = new LabDataHistory;
        $data = $this->request;
        $model->attributes = $data;

        if ($model->save()) {
            Yii::$app->api->sendSuccessResponse($model->attributes);
        } else {
            Yii::$app->api->sendFailedResponse($model->errors);
        }

    }
    
    
     public function actionList($pid)
    {

       
            $dataList = LabDataHistory::findAll(array('product_id' => $pid));
            return $this->renderPartial('_setpoints_list', array('dataList' => $dataList));

    }


    public function actionUpdate($id, $data = null)
    {

        
        $model = $this->findModel($id);//get existing model
        $params = array();
        $phpin  = $this->request; 
        
       //
        $model->attributes = $phpin;
        //$model->attributes = $this->request;

        if ($model->save()) {
            Yii::$app->api->sendSuccessResponse($data);
        } else {
            Yii::$app->api->sendFailedResponse($model->errors);
        }

    }

    public function actionView($id)
    {

        $model = $this->findModel($id);
       $this->sendSuccessResponse($model->attributes);
    }

    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        $model->delete();
        $this->sendSuccessResponse($model->attributes);
    }

    protected function findModel($id)
    {
        if (($model = LabDataHistory::model()->findByPk($id)) !== null) {
            return $model;
        } else {
            $this->sendFailedResponse("Invalid Record requested");
        }
    }
}

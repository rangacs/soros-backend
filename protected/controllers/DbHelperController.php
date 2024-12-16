<?php

class DbHelperController extends Controller
{
	public function actionIndex()
	{
            
            $dbHelper = new DbHelper();
            
            $resutt = $dbHelper->makeDbLive();
		$this->render('index');
	}

}
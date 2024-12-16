<?php
/* @var $this RfidCalTolSettingsController */
/* @var $model RfidCalTolSettings */

$this->breadcrumbs=array(
	'Rfid Cal Tol Settings'=>array('index'),
	$model->wcl_cal_tol_id=>array('view','id'=>$model->wcl_cal_tol_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List RfidCalTolSettings', 'url'=>array('index')),
	array('label'=>'Create RfidCalTolSettings', 'url'=>array('create')),
	array('label'=>'View RfidCalTolSettings', 'url'=>array('view', 'id'=>$model->wcl_cal_tol_id)),
	array('label'=>'Manage RfidCalTolSettings', 'url'=>array('admin')),
);
?>

<h1>Update Rfid CalTolSettings <?php echo $model->wcl_cal_tol_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
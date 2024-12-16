<?php
/* @var $this RfidCalMapController */
/* @var $model RfidCalMap */

$this->breadcrumbs=array(
	'Rfid Cal Maps'=>array('index'),
	$model->wcl_acid=>array('view','id'=>$model->wcl_acid),
	'Update',
);

$this->menu=array(
	array('label'=>'List RfidCalMap', 'url'=>array('index')),
	array('label'=>'Create RfidCalMap', 'url'=>array('create')),
	array('label'=>'View RfidCalMap', 'url'=>array('view', 'id'=>$model->wcl_acid)),
	array('label'=>'Manage RfidCalMap', 'url'=>array('admin')),
);
?>

<h1>Update RfidCalMap <?php echo $model->wcl_acid; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
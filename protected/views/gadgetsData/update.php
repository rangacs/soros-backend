<?php
/* @var $this GadgetsDataController */
/* @var $model GadgetsData */

$this->breadcrumbs=array(
	'Gadgets Datas'=>array('index'),
	$model->gadget_data_id=>array('view','id'=>$model->gadget_data_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GadgetsData', 'url'=>array('index')),
	array('label'=>'Create GadgetsData', 'url'=>array('create')),
	array('label'=>'View GadgetsData', 'url'=>array('view', 'id'=>$model->gadget_data_id)),
	array('label'=>'Manage GadgetsData', 'url'=>array('admin')),
);
?>

<h1>Update GadgetsData <?php echo $model->gadget_data_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
<?php
/* @var $this GadgetsDataController */
/* @var $model GadgetsData */

$this->breadcrumbs=array(
	'Gadgets Datas'=>array('index'),
	$model->gadget_data_id,
);

$this->menu=array(
	array('label'=>'List GadgetsData', 'url'=>array('index')),
	array('label'=>'Create GadgetsData', 'url'=>array('create')),
	array('label'=>'Update GadgetsData', 'url'=>array('update', 'id'=>$model->gadget_data_id)),
	array('label'=>'Delete GadgetsData', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->gadget_data_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GadgetsData', 'url'=>array('admin')),
);
?>

<h1>View GadgetsData #<?php echo $model->gadget_data_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'gadget_data_id',
		'gadget_type',
		'lay_id',
		'widgetsPos',
		'gadget_name',
		'gadget_size',
		'last_updated',
		'data_source',
		'detector_source',
		'group_style',
		'display_style',
	),
)); ?>

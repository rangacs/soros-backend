<?php
/* @var $this RtaTagIndexCompletedController */
/* @var $model RtaTagIndexCompletedNew */

$this->breadcrumbs=array(
	'Rta Tag Index Completed News'=>array('index'),
	$model->tagID,
);

$this->menu=array(
	array('label'=>'List RtaTagIndexCompletedNew', 'url'=>array('index')),
	array('label'=>'Create RtaTagIndexCompletedNew', 'url'=>array('create')),
	array('label'=>'Update RtaTagIndexCompletedNew', 'url'=>array('update', 'id'=>$model->tagID)),
	array('label'=>'Delete RtaTagIndexCompletedNew', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->tagID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage RtaTagIndexCompletedNew', 'url'=>array('admin')),
);
?>

<h1>View RtaTagIndexCompletedNew #<?php echo $model->tagID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'tagID',
		'rtaMasterID',
		'status',
		'tagName',
		'tagGroupID',
		'LocalstartTime',
		'LocalendTime',
		'goodDataSecondsWeight',
		'massflowWeight',
		'validTag',
		'endTic',
		'startTic',
		'goodDataSecs',
		'avgMassFlowTph',
		'totalTons',
		'Ash',
		'Sulfur',
		'Moisture',
		'BTU',
		'Na2O',
		'SO2',
		'TPH',
		'SiO2',
		'Al2O3',
		'Fe2O3',
		'MAFBTU',
		'CaO',
		'K',
	),
)); ?>

<?php
/* @var $this TagCompletedController */
/* @var $model TagCompleted */

$this->breadcrumbs=array(
	'Tag Completeds'=>array('index'),
	$model->tagID,
);

$this->menu=array(
	array('label'=>'List TagCompleted', 'url'=>array('index')),
	array('label'=>'Create TagCompleted', 'url'=>array('create')),
	array('label'=>'Update TagCompleted', 'url'=>array('update', 'id'=>$model->tagID)),
	array('label'=>'Delete TagCompleted', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->tagID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage TagCompleted', 'url'=>array('admin')),
);
?>

<h1>View TagCompleted #<?php echo $model->tagID; ?></h1>

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
		'TEST',
		'CAL_ID',
		'MAFBTU',
		'CaO',
		'MgO',
		'K2O',
		'TiO2',
		'Mn2O3',
		'P2O5',
		'SO3',
		'Cl',
		'LOI',
		'LSF',
		'SM',
		'IM',
		'C4AF',
		'NAEQ',
		'C3S',
		'C3A',
		'SourceDeployed',
		'SourceStored',
		'K',
	),
)); ?>

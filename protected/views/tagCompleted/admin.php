<?php
/* @var $this TagCompletedController */
/* @var $model TagCompleted */

$this->breadcrumbs=array(
	'Tag Completeds'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List TagCompleted', 'url'=>array('index')),
	array('label'=>'Create TagCompleted', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#tag-completed-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Tag Completeds</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'tag-completed-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'tagID',
		'rtaMasterID',
		'status',
		'tagName',
		'tagGroupID',
		'LocalstartTime',
		/*
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
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

<?php
/* @var $this RtaJobTableController */
/* @var $model RtaJobTable */

$this->breadcrumbs=array(
	'Rta Job Tables'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List RtaJobTable', 'url'=>array('index')),
	array('label'=>'Create RtaJobTable', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#rta-job-table-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Rta Job Tables</h1>

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
	'id'=>'rta-job-table-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'jobID',
		'jobStatus',
		'linuxPID',
		'start_time',
		'end_time',
		'backupTable',
		/*
		'tempTable',
		'regenTable',
		'originalTable',
		'originalTableID',
		'loopsFinished',
		'recordsRemaining',
		'recordsTotal',
		'maxID',
		'dateAdded',
		'dateModified',
		'dateCompleted',
		'userAdded',
		'userModified',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

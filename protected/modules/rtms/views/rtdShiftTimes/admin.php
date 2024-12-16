<?php
/* @var $this RtdShiftTimesController */
/* @var $model RtdShiftTimes */

$this->breadcrumbs=array(
	'Rtd Shift Times'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List RtdShiftTimes', 'url'=>array('index')),
	array('label'=>'Create RtdShiftTimes', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#rtd-shift-times-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Rtd Shift Times</h1>

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
	'id'=>'rtd-shift-times-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'shiftTimeID',
		'shiftStart1',
		'shiftDuration1',
		'shiftStart2',
		'shiftDuration2',
		'shiftStart3',
		/*
		'shiftDuration3',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

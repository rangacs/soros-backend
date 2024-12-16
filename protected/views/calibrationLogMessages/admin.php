<?php
/* @var $this CalibrationLogMessagesController */
/* @var $model CalibrationLogMessages */

$this->breadcrumbs=array(
	'Calibration Log Messages'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List CalibrationLogMessages', 'url'=>array('index')),
	array('label'=>'Create CalibrationLogMessages', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#calibration-log-messages-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Calibration Log Messages</h1>

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
	'id'=>'calibration-log-messages-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'cal_msg_id',
		'auto_calib_id',
		'error_type',
		'title',
		'description',
		'updated_at',
		/*
		'updated_by',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

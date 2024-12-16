<?php
/* @var $this RfidCalTolSettingsController */
/* @var $model RfidCalTolSettings */

$this->breadcrumbs=array(
	'Rfid Cal Tol Settings'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List RfidCalTolSettings', 'url'=>array('index')),
	array('label'=>'Create RfidCalTolSettings', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#rfid-cal-tol-settings-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Rfid Cal Tol Settings</h1>

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
	'id'=>'rfid-cal-tol-settings-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'wcl_cal_tol_id',
		'wcl_cal_tol_item_code',
		'Moisture',
		'Moisture_Tol',
		'Ash',
		'Ash_Tol',
		/*
		'Sulfur',
		'Sulfur_Tol',
		'GCV',
		'GCV_Tol',
		'BTU',
		'BTU_Tol',
		'updated_on',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

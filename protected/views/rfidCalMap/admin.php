<?php
/* @var $this RfidCalMapController */
/* @var $model RfidCalMap */

$this->breadcrumbs=array(
	'Rfid Cal Maps'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List RfidCalMap', 'url'=>array('index')),
	array('label'=>'Create RfidCalMap', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#rfid-cal-map-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Rfid Cal Maps</h1>

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
	'id'=>'rfid-cal-map-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'wcl_acid',
		'wcl_item_code',
		'wcl_item_namev',
		'wcl_sabia_cal_name',
		'wcl_updated',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

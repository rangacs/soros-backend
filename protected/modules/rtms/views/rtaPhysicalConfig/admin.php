<?php
/* @var $this RtaPhysicalConfigController */
/* @var $model RtaPhysicalConfig */

$this->breadcrumbs=array(
	'Rta Physical Configs'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List RtaPhysicalConfig', 'url'=>array('index')),
	array('label'=>'Create RtaPhysicalConfig', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#rta-physical-config-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Rta Physical Configs</h1>

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
	'id'=>'rta-physical-config-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'rta_ID_physical',
		'rtaMasterID',
		'IPaddress',
		'goodDataSecondsWeight_physicalCfg',
		'massflowWeight_physicalCfg',
		'analysis_timespan',
		/*
		'averaging_subinterval_secs',
		'detectorID',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

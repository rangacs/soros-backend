<?php
/* @var $this RtaDerivedConfigController */
/* @var $model RtaDerivedConfig */

$this->breadcrumbs=array(
	'Rta Derived Configs'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List RtaDerivedConfig', 'url'=>array('index')),
	array('label'=>'Create RtaDerivedConfig', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#rta-derived-config-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Rta Derived Configs</h1>

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
	'id'=>'rta-derived-config-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'rta_ID_derived',
		'rtaMasterID',
		'data_source_rtaMasterID',
		'filter_type',
		'moving_avg_filter_sample_timespan',
		'kalman_filter_sample_timespan',
		/*
		'massflowWeight_derivedCfg',
		'goodDataSecondsWeight_derivedCfg',
		'percentageGoodDataRequired',
		'kalman_gain_Q',
		'kalman_gain_R',
		'source_decay_comp',
		'source_decay_ref_date',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

<?php
/* @var $this SourceController */
/* @var $model Source */

$this->breadcrumbs=array(
	'Sources'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Source', 'url'=>array('index')),
	array('label'=>'Create Source', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#source-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Sources</h1>

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
	'id'=>'source-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'src_id',
		'src_name',
		'src_type',
		'product_id',
		'src_priority',
		'src_distance',
		/*
		'src_delay',
		'src_min_feedrate',
		'src_max_feedrate',
		'src_proposed_feedrate',
		'src_measured_feedrate',
		'src_cost',
		'src_status',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

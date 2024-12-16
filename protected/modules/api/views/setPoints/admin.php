<?php
/* @var $this SetPointsController */
/* @var $model SetPoints */

$this->breadcrumbs=array(
	'Set Points'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List SetPoints', 'url'=>array('index')),
	array('label'=>'Create SetPoints', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#set-points-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Set Points</h1>

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
	'id'=>'set-points-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'sp_id',
		'product_id',
		'sp_name',
		'sp_value_num',
		'sp_measured',
		'sp_value_den',
		/*
		'sp_const_value_num',
		'sp_const_value_den',
		'sp_tolerance_ulevel',
		'sp_tolerance_llevel',
		'sp_weight',
		'sp_status',
		'sp_priority',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

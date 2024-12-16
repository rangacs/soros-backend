<?php
/* @var $this ElementCompositionController */
/* @var $model ElementComposition */

$this->breadcrumbs=array(
	'Element Compositions'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List ElementComposition', 'url'=>array('index')),
	array('label'=>'Create ElementComposition', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#element-composition-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Element Compositions</h1>

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
	'id'=>'element-composition-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'element_id',
		'source_id',
		'element_name',
		'element_value',
		'element_type',
		'estimated_prob_error',
		/*
		'estimated_max',
		'estimated_min',
		'update_timestamp',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

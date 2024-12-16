<?php
/* @var $this LayoutsController */
/* @var $model Layouts */

$this->breadcrumbs=array(
	'Layouts'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Layouts', 'url'=>array('index')),
	array('label'=>'Create Layouts', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#layouts-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Layouts</h1>

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
	'id'=>'layouts-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'layout_id',
		'name',
		'type',
		'category',
		'is_default',
		'user_id',
		/*
		'created_on',
		'updated_on',
		'created_by',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

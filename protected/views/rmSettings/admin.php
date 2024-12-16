<?php
/* @var $this RmSettingsController */
/* @var $model RmSettings */

$this->menu=array(
	array('label'=>'List RmSettings', 'url'=>array('index')),
	array('label'=>'Create RmSettings', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#rm-settings-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'rm-settings-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'varName',
		//'varKey',
		'varValue',
		/*
		array(
			'class'=>'CButtonColumn',
		),
		*/
	),
)); ?>

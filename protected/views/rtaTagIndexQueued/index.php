<style>
    .blackData {color: black}
</style>

<?php
$this->breadcrumbs=array(
	'Tag Queue',
);

$this->menu=array(
	array('label'=>'Create RtaTagIndexQueued', 'url'=>array('create')),
	array('label'=>'Manage RtaTagIndexQueued', 'url'=>array('admin')),
);
?>

<h1>Tag Queue</h1>

<?php 
	$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
    'rowCssClassExpression' => '"blackData"',
	'columns'=>array(
		/*'lay_id',*/
		/*'a_href',*/
		'tagID',
		'status',
		/*'gadPlacement',*/
		'tagName',
		'LocalendTime',
		array(
			'class'=>'CButtonColumn',
		),//array
	),//columns
    
	//'itemView'=>'_view',
)); ?>
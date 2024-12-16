<?php
/* @var $this RtaDerivedConfigController */
/* @var $model RtaDerivedConfig */

$this->breadcrumbs=array(
	'Rta Derived Configs'=>array('index'),
	$model->rta_ID_derived=>array('view','id'=>$model->rta_ID_derived),
	'Update',
);

$this->menu=array(
	array('label'=>'List RtaDerivedConfig', 'url'=>array('index')),
	array('label'=>'Create RtaDerivedConfig', 'url'=>array('create')),
	array('label'=>'View RtaDerivedConfig', 'url'=>array('view', 'id'=>$model->rta_ID_derived)),
	array('label'=>'Manage RtaDerivedConfig', 'url'=>array('admin')),
);
?>

<h1>Update RtaDerivedConfig <?php echo $model->rta_ID_derived; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
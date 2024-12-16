<?php
/* @var $this MasterTemplateiiiController */
/* @var $data MasterTemplateiii */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('_key_')); ?>:</b>
	<?php echo CHtml::encode($data->_key_); ?>
	<br />


</div>
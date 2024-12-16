<?php
/* @var $this TagCompletedController */
/* @var $data TagCompleted */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('tagID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->tagID), array('view', 'id'=>$data->tagID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('rtaMasterID')); ?>:</b>
	<?php echo CHtml::encode($data->rtaMasterID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tagName')); ?>:</b>
	<?php echo CHtml::encode($data->tagName); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tagGroupID')); ?>:</b>
	<?php echo CHtml::encode($data->tagGroupID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('LocalstartTime')); ?>:</b>
	<?php echo CHtml::encode($data->LocalstartTime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('LocalendTime')); ?>:</b>
	<?php echo CHtml::encode($data->LocalendTime); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('goodDataSecondsWeight')); ?>:</b>
	<?php echo CHtml::encode($data->goodDataSecondsWeight); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('massflowWeight')); ?>:</b>
	<?php echo CHtml::encode($data->massflowWeight); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('validTag')); ?>:</b>
	<?php echo CHtml::encode($data->validTag); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('endTic')); ?>:</b>
	<?php echo CHtml::encode($data->endTic); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('startTic')); ?>:</b>
	<?php echo CHtml::encode($data->startTic); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('goodDataSecs')); ?>:</b>
	<?php echo CHtml::encode($data->goodDataSecs); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('avgMassFlowTph')); ?>:</b>
	<?php echo CHtml::encode($data->avgMassFlowTph); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('totalTons')); ?>:</b>
	<?php echo CHtml::encode($data->totalTons); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Ash')); ?>:</b>
	<?php echo CHtml::encode($data->Ash); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Sulfur')); ?>:</b>
	<?php echo CHtml::encode($data->Sulfur); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Moisture')); ?>:</b>
	<?php echo CHtml::encode($data->Moisture); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('BTU')); ?>:</b>
	<?php echo CHtml::encode($data->BTU); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Na2O')); ?>:</b>
	<?php echo CHtml::encode($data->Na2O); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('SO2')); ?>:</b>
	<?php echo CHtml::encode($data->SO2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('TPH')); ?>:</b>
	<?php echo CHtml::encode($data->TPH); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('SiO2')); ?>:</b>
	<?php echo CHtml::encode($data->SiO2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Al2O3')); ?>:</b>
	<?php echo CHtml::encode($data->Al2O3); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Fe2O3')); ?>:</b>
	<?php echo CHtml::encode($data->Fe2O3); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('TEST')); ?>:</b>
	<?php echo CHtml::encode($data->TEST); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('CAL_ID')); ?>:</b>
	<?php echo CHtml::encode($data->CAL_ID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('MAFBTU')); ?>:</b>
	<?php echo CHtml::encode($data->MAFBTU); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('CaO')); ?>:</b>
	<?php echo CHtml::encode($data->CaO); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('MgO')); ?>:</b>
	<?php echo CHtml::encode($data->MgO); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('K2O')); ?>:</b>
	<?php echo CHtml::encode($data->K2O); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('TiO2')); ?>:</b>
	<?php echo CHtml::encode($data->TiO2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Mn2O3')); ?>:</b>
	<?php echo CHtml::encode($data->Mn2O3); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('P2O5')); ?>:</b>
	<?php echo CHtml::encode($data->P2O5); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('SO3')); ?>:</b>
	<?php echo CHtml::encode($data->SO3); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Cl')); ?>:</b>
	<?php echo CHtml::encode($data->Cl); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('LOI')); ?>:</b>
	<?php echo CHtml::encode($data->LOI); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('LSF')); ?>:</b>
	<?php echo CHtml::encode($data->LSF); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('SM')); ?>:</b>
	<?php echo CHtml::encode($data->SM); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('IM')); ?>:</b>
	<?php echo CHtml::encode($data->IM); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('C4AF')); ?>:</b>
	<?php echo CHtml::encode($data->C4AF); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NAEQ')); ?>:</b>
	<?php echo CHtml::encode($data->NAEQ); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('C3S')); ?>:</b>
	<?php echo CHtml::encode($data->C3S); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('C3A')); ?>:</b>
	<?php echo CHtml::encode($data->C3A); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('SourceDeployed')); ?>:</b>
	<?php echo CHtml::encode($data->SourceDeployed); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('SourceStored')); ?>:</b>
	<?php echo CHtml::encode($data->SourceStored); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('K')); ?>:</b>
	<?php echo CHtml::encode($data->K); ?>
	<br />

	*/ ?>

</div>
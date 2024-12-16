<?php
/* @var $this TestCalibController */

$this->breadcrumbs=array(
	'Test Calib',
);


	


$autoCalibObject->getValidLabRecords();
$autoCalibObject->getAnalysisData();

$autoCalibObject->calculateScale();
$autoCalibObject->saveAutoCalib();
//var_dump($autoCalibObject->analysisObject->avgDataVector);

?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<p>
	You may change the content of this page by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
</p>

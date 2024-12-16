<?php

 $about         = Yii::t('UI_about_header', 'about');
 $about_summary = Yii::t('UI_about_summary', 'about_summary');
 
 $this->pageTitle=Yii::app()->name." - ".$about;
 $this->breadcrumbs=array(
  	$about,
 );
?>

<h1><?php echo $about; ?></h1>

<p class="welcome"><?php echo $about_summary; ?></p>

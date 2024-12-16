<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'tag-group-form',
        'enableAjaxValidation' => false,
    ));
    ?>

   
    <?php echo $form->errorSummary($model); ?>


    <div class="clearfix">

        <label class="form-label" for="form-name"><?php echo Yii::t('app','Tag Group Name')?>  </label>

        <div class="form-input">

            <?php echo $form->textField($model, 'tagGroupName', array('size' => 60, 'maxlength' => 120)); ?>
        </div>
    </div>







    <div class="clearfix">

        <label class="form-label" for="form-name"> </label>

        <div class="form-input">

            <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save')); ?>
        </div>
    </div>



    <?php $this->endWidget(); ?>

</div><!-- form -->




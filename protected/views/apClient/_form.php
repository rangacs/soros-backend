<?php
/* @var $this TagQueuedController */
/* @var $model TagQueued */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $tagGroups = CHtml::listData(
                    TagGroup::model()->findAll(), 'tagGroupID', 'tagGroupName'
    );

    
    $startDate = date('Y-m-d',time() - 60 * 60);
    $startTime = date("H:i",time() - 60 * 60);
    $endDate   = date('Y-m-d',time());
    $endTime   = date("H:i",time());

    $masterIds = CHtml::listData(
    ConfigMaster::model()->findAll(), 'rtaMasterID', 'DB_ID_string');
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'tag-queued-form',
            // Please note: When you enable ajax validation, make sure the corresponding
            // controller action is handling ajax validation correctly.
            // There is a call to performAjaxValidation() commented in generated controller code.
            // See class documentation of CActiveForm for details on this.
            'enableAjaxValidation' => false,
        ));
        ?>


    <div class="clearfix">

        <label class="form-label" for="form-name"></label>

        <div class="form-input">

            <?php echo Yii::t('app', '<p class="note">Fields with <span class="required">*</span> are required.</p>'); ?> 

            <?php echo $form->errorSummary($model); ?> </div>
    </div>


    <div class="clearfix">

        <label class="form-label" for="form-name"><?php echo Yii::t('app', 'Name'); ?></label>

        <div class="form-input">

            <?php echo $form->textField($model, 'tagName', array('size' => 30, 'maxlength' => 30)); ?>
        </div>
    </div>
    
<?php
                $cfg = new ConfigFile();
				$calAdjust = CAL_ADJUST_FILE;
				$cfg->load($calAdjust);
				$cfg->setPath("/STDCAL");
				$calibFile = $cfg->readEntry("std_display_list");

?>    
    <div class="clearfix">

        
        <input type="hidden" name="active_calibration" value="<?php echo $calibFile ?>"/>
       
    </div>


    <div class="clearfix hide">

        <label class="form-label" for="form-name"><?php echo Yii::t('app', 'Master ID'); ?> </label>

        <div class="form-input">

            <?php
            echo CHtml::activeDropDownList($model, 'rtaMasterID', $masterIds, array('empty' => Yii::t('app', 'Select Option'))
            );
            ?>
        </div>
    </div>

    <div class="clearfix ">

        <label class="form-label" for="form-name"><?php echo Yii::t('app', 'Tag Group'); ?></label>

        <div class="form-input">

            <?php
            echo CHtml::activeDropDownList($model, 'tagGroupID', $tagGroups, array('empty' => Yii::t('app', 'Select Option'))
            );
            ?>
        </div>
    </div>


        <div class="clearfix ">
            <label class="form-label" for="form-name"><?php echo Yii::t('dash', 'Start Time'); ?> <em>*</em></label>
            <div class="form-input">
                <input type="text" required="required" name="TagQueued_startDate" id="TagQueued_startDate" autocomplete="off" value="<?= $startDate?>">
                
                <div class="fr">
                     <input type="text" name="TagQueued_startTime" id="TagQueued_startTime" class="" size="5" max="5" value="<?= $startTime?>"/>
                
                </div> 
            </div>
        </div>                                            
        <div class="clearfix ">
            <label class="form-label" for="form-name">
                <?php echo Yii::t('dash', 'End Time'); ?> <em>*</em></label>
            <div class="form-input">
                <input type="text" required="required" name="TagQueued_endDate" id="TagQueued_endDate" autocomplete="off" value="<?=  $endDate?>">
                
                <div class="fr">
                    <input type="text" name="TagQueued_endTime" id="timeRangeEnd_time" class="" size="5" maxlength="5" value="<?= $endTime?>" />
                </div>
            </div>                                                
        </div> 

    <div class="clearfix">
        <div class="form-input">

            <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'), array('id' => 'subMB')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->



<script type="text/javascript">

    $("#subMB").click(function() {
        if($.datepicker.parseDate("yy-mm-dd",$("#TagQueued_startDate").val()) == $.datepicker.parseDate("yy-mm-dd",$("#TagQueued_endDate").val())) {
            
            var t1 = hmsToSecondsOnly($("#TagQueued_startTime").val());
            var t2 = hmsToSecondsOnly($("#timeRangeEnd_time").val());
            if ((t1 > t2)) {
                alert("Tag Start Time has to be less than End Time");
                return false;
            }
        }else if($.datepicker.parseDate("yy-mm-dd",$("#TagQueued_startDate").val()) > $.datepicker.parseDate("yy-mm-dd",$("#TagQueued_endDate").val())) {
                alert("Tag Start Time has to be less than End Time");
                return false;
        }
        
    });

    $('#TagQueued_startDate').datepicker({
  dateFormat: "yy-mm-dd"
});
    $('#TagQueued_endDate').datepicker({
  dateFormat: "yy-mm-dd"
});
    $('.timepick').timeslider({showValue: true, clickable: true});
    
    function hmsToSecondsOnly(str) {
        var p = str.split(':'),
            s = 0, m = 1;

        while (p.length > 0) {
            s += m * parseInt(p.pop(), 10);
            m *= 60;
        }

        return s;
    }

</script>
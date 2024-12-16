<style>
    #tagGroup_startDate,#tagGroup_endDate{

        width :63% !important;
    }
    #search-table td{
        padding:  2px ! important;
    }
</style>
<section class="main-section grid_8">
    <nav class="">

        <?php
        $baseUrl = Yii::app()->basePath;
        $tagGroups = CHtml::listData(
                        TagGroup::model()->findAll(), 'tagGroupID', 'tagGroupName'
        );

        $keys = array_keys($tagGroups);
        $selectedGroup = $tagGroupObject->tagGroupID ? $tagGroupObject->tagGroupID : $keys[0];
        $tagName = isset($_POST['tagGroup_tagName']) ? $_POST['tagGroup_tagName'] : "";


        $menuFile = $baseUrl . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "themes" . DIRECTORY_SEPARATOR . "tutorialzine1" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "pageDefaults" . DIRECTORY_SEPARATOR . "authLeftMenu.php";
        include_once($menuFile);

        if (isset($_REQUEST['tagGroup_startTime']) && isset($_REQUEST['tagGroup_endTime'])) {
            $strtDateStirng = $_POST["tagGroup_startDate"] . " " . $_POST["tagGroup_startTime"];
            $endDateString = $_POST["tagGroup_endDate"] . " " . $_POST["tagGroup_endTime"];

            $startDate = date('d-m-Y', strtotime($strtDateStirng));
            $endDate = date('d-m-Y', strtotime($endDateString));


            $startTime = date('H:i', strtotime($strtDateStirng));
            $endTime = date('H:i', strtotime($endDateString));
        } else {


            $startDate = date("d-m-Y", strtotime($tagGroupObject->getStartTime()));
            $startTime = date("H:i", strtotime($tagGroupObject->getStartTime()));
            $endDate = date("d-m-Y", strtotime($tagGroupObject->getEndTime()));
            $endTime = date("H:i", strtotime($tagGroupObject->getStartTime()));
        }
        ?>
    </nav>
    <div class="main-content">


        <div class="grid_6 leading" style="min-height:250px !important;">

            <section class="container_6 clearfix">

                <!-- Tabs inside Portlet -->
                <div class="grid_6 leading">
                    <header class="ui-widget-header ui-corner-top form">

                        <?php
                        $form = $this->beginWidget('CActiveForm', array(
                            'id' => 'tag-queued-form',
                            // Please note: When you enable ajax validation, make sure the corresponding
                            // controller action is handling ajax validation correctly.
                            // There is a call to performAjaxValidation() commented in generated controller code.
                            // See class documentation of CActiveForm for details on this.
                            'enableAjaxValidation' => false,
                            'method' => 'POST',
                            'action' => Yii::app()->createAbsoluteUrl('tagGroup/search')
                        ));
                        ?>   

                        <table id="search-table">
                            <tr> 

                                <td>
                                    <div class="clearfix ">
                                        <label class="form-label" for="form-name"><?php echo Yii::t('dash', 'Tag Group'); ?> 
                                        </label>
                                        <div class="form-input">
                                            <?php
                                            echo CHtml::dropDownList('tagGroup_tagGroupID', $selectedGroup, $tagGroups, array('empty' => 'Select Option'));
                                            ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="clearfix ">
                                        <label class="form-label" for="form-name"><?php echo Yii::t('dash', 'Tag Name'); ?> </label>
                                        <div class="form-input">
                                            <input type="text"  name="tagGroup_tagName" id="tagGroup_tagName" autocomplete="off" size="15" max="15" value="<?php echo $tagName ?>">

                                        </div>
                                    </div> 
                                </td>
                                <td>
                                    <div>
                                        <input type="checkbox" id="search_name_only" name="search_name_only" value="on"
                                              <?php echo isset($_POST['search_name_only']) ? 'checked' : '';?>>
                                        <label for="scales">Search only tag names</label>
                                    </div>
                                </td>


                            </tr>
                            <tr> <td>
                                    <div class="clearfix ">
                                        <label class="form-label" for="form-name"><?php echo Yii::t('dash', 'Start Time'); ?> </label>
                                        <div class="form-input">
                                            <input type="text" required="required" name="tagGroup_startDate" id="tagGroup_startDate" autocomplete="off" size="15" max="15" value="<?= $startDate ?>">

                                            <div class="fr">
                                                <input type="text" name="tagGroup_startTime" id="tagGroup_startTime" class="" size="5" max="5" value="<?= $startTime ?>"/>

                                            </div> 
                                        </div>
                                    </div>  
                                </td>
                                <td >

                                    <div class="clearfix ">
                                        <label class="form-label" for="form-name">
                                            <?php echo Yii::t('dash', 'End Time'); ?> </label>
                                        <div class="form-input">
                                            <input type="text" required="required" name="tagGroup_endDate" id="tagGroup_endDate" autocomplete="off" value="<?= $endDate ?>">

                                            <div class="fr">
                                                <input type="text" name="tagGroup_endTime" id="timeRangeEnd_time" class="" size="5" maxlength="5" value="<?= $endTime ?>" />
                                            </div>
                                        </div>                                                
                                    </div> 
                                </td>
                                <td width="15%" rowspan="2">
                                    <button data-icon-primary="ui-icon-search" class="ui-button ui-widget ui-state-default ui-corner-all ui-state-focus ui-button-text-icon-primary" role="button">
                                        <span class="ui-button-icon-primary ui-icon ui-icon-search"></span><span class="ui-button-text"> Search
                                        </span>
                                    </button> <a href="<?php echo Yii::app()->createAbsoluteUrl('tagGroup/search') ?>">clear </a>
                                </td>
                            </tr>
                        </table>
<?php $this->endWidget(); ?>

                    </header>

                    <section id="section_rawMix" class="ui-widget-content ui-corner-bottom" style="min-height:600px;">

                        <section id="portlet-set-point"  class=" ui-tabs-panel ui-widget-content ui-corner-bottom">


                            <?php
                            $tags = $tagGroupObject->getAllTags();

                            $elements = LabUtility::spElements();

                            TagGroupHelper::renderTagtable($tagGroupObject, $elements);
                            ?>

                        </section>

                </div>
            </section>

        </div>

        <div class="clear"><br/></div>



    </div>
</section>


<script type="text/javascript">

    $('#tagGroup_startDate').datepicker({
        dateFormat: "dd-mm-yy"
    });
    $('#tagGroup_endDate').datepicker({
        dateFormat: "dd-mm-yy"
    });
    selectedTagGroup = '<?php $selectedGroup ?>';
    window.onload = function () {

        $("#tagGroup_tagGroupID").live('change', function () {

            $("#tag-queued-form").submit();
        })//live end

    }

</script>
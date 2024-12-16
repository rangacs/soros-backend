<?php

$tagGroups = CHtml::listData(
                TagGroup::model()->findAll(), 'tagGroupID', 'tagGroupName'
);

$keys = array_keys($tagGroups);



$defTagGrpId = TagGroup::getDefaultTagGroupID();
$selectedGroup = isset($_REQUEST['TagGroup']['tagGroupID']) ? $_REQUEST['TagGroup']['tagGroupID'] : $defTagGrpId;


$tagName = "";

if (isset($_REQUEST['tagGroup_tagName'])) {

    $tagName = $_REQUEST['tagGroup_tagName'];
}

$newTagUrl = Yii::app()->createAbsoluteUrl('tagSettings/create');


if (isset($_REQUEST['tagGroup_startTime']) && isset($_REQUEST['tagGroup_endTime'])) {
    $strtDateStirng = $_POST["tagGroup_startDate"] . " " . $_POST["tagGroup_startTime"];
    $endDateString = $_POST["tagGroup_endDate"] . " " . $_POST["tagGroup_endTime"];

    $startDate = date('d-m-Y', strtotime($strtDateStirng));
    $endDate = date('d-m-Y', strtotime($endDateString));


    $startTime = date('H:i', strtotime($strtDateStirng));
    $endTime = date('H:i', strtotime($endDateString));
} else {

    $tagGroupObject = TagGroup::model()->find('tagGroupID = :tgID', array(':tgID' => $selectedGroup));
    
    $startDate = date("d-m-Y", strtotime($tagGroupObject->getStartTime()));
    $startTime = date("H:i", strtotime($tagGroupObject->getStartTime()));
    $endDate = date("d-m-Y", strtotime($tagGroupObject->getEndTime()));
    $endTime = date("H:i", strtotime($tagGroupObject->getStartTime()));
        //        echo "sqmy";

}



?>
<style type="text/css">

    .flex-container{
        display: flex; /* or inline-flex */
        justify-content: center;
        align-items: center;
    }
    .flex-container tr{
        /*height: 30px;*/
    }
    .float{
        position:fixed;
        width:100px;
        height:60px;
        top:400px;
        left:40px;
        background-color:transparent;
        /*color:#FFF;*/
        /*border-radius:50px;*/
        /*text-align:center;*/
        /*box-shadow: 2px 2px 3px #999;*/
    }

    .my-float{
        margin-top:22px;
    }
    .dropdownListCss select {
        background-color: #447ba2;
        color: white;
        padding: 12px;
        width: 200px;
        border: none;
        font-size: 20px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.6);
        -webkit-appearance: button;
        appearance: button;
        outline: none;
    }

    #search-table td,th{
        padding: 2px ! important;

    }

    .sidenav {
        height: 100%;
        width: 0;
        position: fixed;
        z-index: 9999;
        top: 0;
        right: 0;
        background-color: white;
        overflow-x: hidden;
        transition: 0.5s;
        padding-top: 60px;
    }

    .sidenav a {
        padding: 8px 8px 8px 32px;
        text-decoration: none;
        font-size: 25px;
        color: #818181;
        display: block;
        transition: 0.3s;
    }

    .sidenav a:hover {
        color: #f1f1f1;
    }

    .sidenav .closebtn {
        position: absolute;
        top: 0;
        right: 25px;
        font-size: 36px;
        margin-left: 50px;
    }

    @media screen and (max-height: 450px) {
        .sidenav {padding-top: 15px;}
        .sidenav a {font-size: 18px;}
    }


</style>
<div id="merging" class="sidenav">
    <section class="container_6 clearfix">
        <!-- Tabs inside Portlet -->
        <div class="grid_6 leading">
            <header class="ui-widget-header ui-widget-header-blue ui-corner-top">
                <h2> 
                    <?php echo Yii::t('app', 'Merge Tags'); ?>
                </h2>


                <div class="clearfix"></div>
            </header>
            <section id="merging_content" class="ui-widget-content ui-corner-bottom">

            </section>
        </div>

    </section>

</div>
<section class="main-section grid_8">
    <nav>
        <!-- Abhinandan. Invoke the script responsible for rendering the Left Side Bar Menu upon page load.. -->
        <?php
        $baseUrl = Yii::app()->basePath;

        $menuFile = $baseUrl . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "themes" . DIRECTORY_SEPARATOR . "tutorialzine1" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "pageDefaults" . DIRECTORY_SEPARATOR . "authLeftMenu.php";
        include_once($menuFile);
        
        ?>
    </nav>
    <div class="main-content">

        <section class="container_6 clearfix">

            <!-- Tabs inside Portlet -->
            <div class="grid_6 leading">
                <header class="ui-widget-header ui-widget-header-blue ui-corner-top">
                    <h2> <?php echo Yii::t('app', 'Tags'); ?></h2>


                    <a style="margin-right:15px;margin-top: -15px;font-weight:bold;" data-icon-primary="ui-icon-circle-plus"  href="<?php echo $newTagUrl ?>"  class=" pull-right ui-button ui-widget ui-state-highlight ui-corner-all ui-state-focus ui-button-text-icon-primary" role="button">
                        <span class="ui-button-icon-primary ui-icon ui-icon-circle-plus"></span><span class="ui-button-text">
                            <?php echo Yii::t('app', 'New Tag'); ?>
                        </span>
                    </a>
                    <div class="clearfix"></div>
                </header>
                <section id="section_rawMix" class="ui-widget-content ui-corner-bottom" style="min-height:600px;">

                    <section id="portlet-set-point" style="min-height:300px;" class=" ui-tabs-panel ui-widget-content ui-corner-bottom">


                        <div class="form">

                            <?php
                            $form = $this->beginWidget('CActiveForm', array(
                                'id' => 'tag-queued-form',
                                'enableAjaxValidation' => false,
                                'method' => 'POST',
                                'action' => Yii::app()->createAbsoluteUrl('tagSettings/index')
                            ));
                            ?>   
                            <table id="search-table">
                                <tr> 

                                    <td>
                                        <div class="clearfix ">
                                            <label class="form-label" for="form-name"><?php echo Yii::t('dash', 'Tag Group'); ?> 
                                            </label>
                                            <div class="form-input">


                                                <span style="padding:15px;font:14px solid;color:blue;" class="dropdownListCss"> 
                                                    <?php
                                                    echo CHtml::dropDownList('TagGroup[tagGroupID]', $selectedGroup, $tagGroups);
                                                    ?>
                                                </span>
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
                                            <input type="checkbox" id="search_name_only" name="search_name_only"  checked="checked" value="on"
                                                   <?php echo isset($_POST['search_name_only']) ? 'checked' : ''; ?>>
                                            <label for="scales">Only tag names</label>
                                        </div>
                                    </td>


                                </tr>
                                <tr> <td>
                                        <div class="clearfix ">
                                            <label class="form-label" for="form-name"><?php echo Yii::t('dash', 'Start Time'); ?> </label>
                                            <div class="form-input">
                                                <input type="text" style="width:140px" required="required" name="tagGroup_startDate" id="tagGroup_startDate" autocomplete="off" size="15" max="15" value="<?= $startDate ?>">

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
                                                <input type="text" style="width:140px" required="required" name="tagGroup_endDate" id="tagGroup_endDate" autocomplete="off" value="<?= $endDate ?>">

                                                <div class="fr">
                                                    <input type="text" name="tagGroup_endTime" id="timeRangeEnd_time" class="" size="5" maxlength="5" value="<?= $endTime ?>" />
                                                    <input type="hidden" id="pageHid" name="page" value="" />
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
                                
                                </tr>
                            </table>
                            <?php $this->endWidget(); ?>

                        </div>
                        <form id="tg-form" class="float-left " > 



                        </form>

                        <section>
                            <div class="tabs ui-tabs ui-widget ui-widget-content ui-corner-all">
                                <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-top">
                                    <li class="ui-state-default ui-corner-top">
                                        <a href="#create">   <?php echo Yii::t('app', 'Tags Queued'); ?>
                                        </a>

                                    </li>
                                </ul>
                                <section id="create" class="ui-tabs-panel ui-widget-content ui-corner-bottom">

                                    <?php
                                    $tagGroupObject = TagGroup::model()->find('tagGroupID = :tgID', array(':tgID' => $selectedGroup));
                                    $elements = HeliosUtility::getDisplayElements();
                                    TagQueuedHelper::renderTagtable($tagGroupObject, $elements);
                                    ?>


                                </section>

                            </div>
                        </section>

                        <section>
                            <div class="tabs ui-tabs ui-widget ui-widget-content ui-corner-all">
                                <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-top">
                                    <li class="ui-state-default ui-corner-top"><a href="#create"> <?php echo Yii::t('app', 'Tags Completed'); ?>  </a></li>
                                </ul>
                                <section id="create" class="ui-tabs-panel ui-widget-content ui-corner-bottom">

                                    <?php
                                    TagGroupHelper::renderTagtable($tagGroupObject, $elements);
                                    include_once("ppagination.php");
                                    ?>
                                </section>

                            </div>
                        </section>


                    </section>
                </section>
            </div><!--  grid_6 -->
        </section>

    </div>
</section>

<div class="float " >

    <a id="merge_button" onclick="getMergeView()" style="display:none;margin-right:15px;margin-top: -15px;font-weight:bold;" data-icon-primary="ui-icon-circle-plus"  href="#"  class=" pull-right ui-button ui-widget ui-state-highlight ui-corner-all ui-state-focus ui-button-text-icon-primary" role="button">
        <span class="ui-button-text">
            <?php echo Yii::t('app', 'Merge'); ?>
        </span>
    </a>
</div>

<div id="merge_settings" class="flex-container">

</div>
<script type="text/javascript">
    function openNav() {
//        document.getElementById("mySidenav").style.width = "450px";
//        document.getElementById("merging").style.width = "450px";

        $("#merge_settings").dialog('open');

    }

    function openSearchBar() {
        document.getElementById("mySidenav").style.width = "450px";
    }

    function closeSearchBar() {
        document.getElementById("mySidenav").style.width = "0px";
    }
    function closeNav() {
//        document.getElementById("mySidenav").style.width = "0";
        document.getElementById("merging").style.width = "0";
    }

    $('#tagGroup_startDate').datepicker({
        dateFormat: "dd-mm-yy"
    });
    $('#tagGroup_endDate').datepicker({
        dateFormat: "dd-mm-yy"
    });
    window.onload = function () {



        $("#merge_settings").dialog({
            autoOpen: false,
            height: 300,
            width: 700,
            modal: true,
            title: "Merge Tags",
            open: function (event, ui) {
            },
            close: function () {

            }
        });


        $("input:checkbox").live('change', function () {
            var tagList = getCheckTagList();

            if (tagList.length >= 2) {
                $("#merge_button").show();
            } else {
                $("#merge_button").hide();
            }
//            $("#merge_button").show();
//            mergetags(tagList);

        });

        $("#TagGroup_tagGroupID").live('change', function () {

            $("#tag-queued-form").submit();
        })
    }

    function hasQueuedTag() {

        var hasQueuedTag = false;
        $("#queued-table input:checkbox").each(function (index) {

            var att = $(this).attr('checked');
//                console.log(att);
            if (att) {
                hasQueuedTag = true;

            }

        });
        return hasQueuedTag;
    }

    function getCheckTagList() {
        var tagList = [];

        $("input:checkbox").each(function (index) {

            var att = $(this).attr('checked');
//                console.log(att);
            if (att) {
                console.log(index + ": " + $(this).val());
                tagList.push($(this).val());
            }

        });



        return tagList;
    }

    function getMergeView() {
//       console.log(taglist);
        var taglist = getCheckTagList()
        var tagGroup = $("#TagGroup_tagGroupID").val()
        $.ajax({
            url: "<?php echo Yii::app()->createAbsoluteUrl('tagSettings/DisplayMerge') ?>",
            data: {taglist: taglist.join(","), tagGroup: tagGroup},
            success: function (response) {
//                alert(response)
                $("#merge_settings").html(response);
                openNav();
            }
        })
    }
    function close() {

        $("#merge_settings").dialog('close');
    }
    function mergetags() {
//        console.log(taglist);
        var taglist = getCheckTagList();
        var tagGroup = $("#TagGroup_tagGroupID").val();
        var new_tag_name = $("#merged_tag_name").val();
        var hasQueuedTagFlag = hasQueuedTag();
        $.ajax({
            url: "<?php echo Yii::app()->createAbsoluteUrl('tagSettings/merge') ?>",
            data: {taglist: taglist.join(","), tagGroup: tagGroup, newTagName: new_tag_name, hasQueuedTag: hasQueuedTagFlag},
            success: function () {

//                alert("Merging completed");
                $("#merge_settings").html('Tags merged Successfully');
//                window.location = "";
            }
        })
    }

    function getSubTag(tagId) {

//        alert(tagId)
        $.ajax({
            url: "<?php echo Yii::app()->createAbsoluteUrl('tagSettings/SubTagViews') ?>",
            data: {tagID: tagId},
            success: function (response) {

                $("#merge_settings").html(response);
                $("#merge_settings").dialog('open');
//                alert(response);
//                window.location = "";
            }
        })

    }

</script>

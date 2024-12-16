<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TagGroupHelper
 *
 * @author veda
 */
class TagQueuedHelper {
    //put your code here

    /**
     * 
     * @param TagCompleted  $tagObject
     * @param array $elements
     */

    /**
     * 
     * @param TagGroup $tagObject
     * @param boolean $elements
     */
    public static function renderTagtable($tagGroupObject, $elements) {

        $tCriteria = new CDbCriteria;
        $tCriteria->compare('tagGroupID', $tagGroupObject->tagGroupID);
        $tCriteria->order = 'tagID DESC';

        $queuedTags = TagQueued::model()->findAll($tCriteria);

        //$queuedTags = TagQueued::model()->findAll('tagGroupID = :tgid', array(':tgid' => $tagGroupObject->tagGroupID));
        //  return $tagsArray;
        echo '<table class="full margin-top-20 list-table " id="queued-table">';

        self::renderHeader($elements);

        self::renderBody($queuedTags, $elements);

        echo '</table>';

        self::initDataTable();
    }

    public static function renderHeader($elements) {

        echo '<thead><tr>';
        echo '<th class="ui-state-default ui-corner-top"></th>';
        echo '<th class="ui-state-default ui-corner-top">' . Yii::t('app', 'Tag Name') . '</th>';
        //echo '<th class="ui-state-default ui-corner-top">' . Yii::t('app', 'Status') . '</th>';
        //echo '<th class="ui-state-default ui-corner-top">' . Yii::t('app', 'Tag Group') . '</th>';
//        echo '<th class="ui-state-default ui-corner-top">' . Yii::t('app', 'Calib') . '</th>';
        echo '<th class="ui-state-default ui-corner-top">' . Yii::t('app', 'Start Time') . '</th>';
        echo '<th class="ui-state-default ui-corner-top">' . Yii::t('app', 'End Time') . '</th>';

        foreach ($elements as $ele) {


            if ($ele == "BTU")
                $ele = "GCV";
            echo '<th class="ui-state-default ui-corner-top">' . Yii::t('app', $ele) . '</th>';
        }

        echo '<th class="ui-state-default ui-corner-top">' . Yii::t('app', 'Action') . '</th></tr></thead>';
    }

    /**
     * 
     * @param TagQueued $tags
     * @param type $elements
     */
    public static function renderBody($tags, $elements) {

        $dateTimeFormat = RmSettings::getValueFromKey("GLOBAL_TIME_FORMAT1", 'd-m-y H:i');
        foreach ($tags as $qTag) {

            $interval = new IntervalObject();

            $interval->setStartTime($qTag->LocalstartTime);
            $interval->setEndTime($qTag->LocalendTime);
            $record = $qTag->getRecords();
            $analysisDataProvider = new AnalysisDataProvider();
            $analysisDataProvider->setElements($elements);

//            $record = $analysisDataProvider->getTagAvg($qTag);
            
//            var_dump($record);
//            die();/

            $viewurl = Yii::app()->createAbsoluteUrl('tagSettings/tagView', array('id' => $qTag->tagID, 'type' => 'queued'));
            $stopurl = Yii::app()->createAbsoluteUrl("tagQueued/update", array('id' => $qTag->tagID, 'stg' => 1));
            $editUrl = Yii::app()->createAbsoluteUrl("tagQueued/update", array('id' => $qTag->tagID));
            echo '<tr>';


            $query = "select count(tagID)  as count  from rta_tag_index_sub_tag where tagID='" . $qTag->tagID . "'";
 
            $result = Yii::app()->db->createCommand($query)->queryScalar();
            if ($result > 1) {
                $subView = '<span style="display:table-cell" class="ui-button-icon-primary ui-icon ui-icon-circle-plus" onclick="getSubTag(' . $qTag->tagID . ')"></span>';
            
                $checkBox = "";
            } else {
                $subView = "";
                $checkBox = '<input name="tag_checkbox" value="' . $qTag->tagID . '" type="checkbox">';
            }
            
            echo '<td>'.$checkBox.'</td>';

//            $subView = '<span class="ui-button-icon-primary ui-icon ui-icon-circle-plus"></span>';
            echo "<td> <div style='display:table'>" . $subView . " <a href='" . $viewurl . "'  style='color:blue;'>" . $qTag->tagName . "</a></div></td>";

            //echo "<td> " . TagGroupHelper::getNameFromID($qTag->tagGroupID) . "</td>";
//            echo "<td> " . WclRfidCalMap::findActiveCalibFile($tripInfo["w_matCode"]) . "</td>";
            echo "<td> " . date($dateTimeFormat, strtotime($qTag->LocalstartTime)) . "</td>";
            echo "<td> " . date($dateTimeFormat, strtotime($qTag->LocalendTime)) . "</td>";

            foreach ($elements as $ele) {

                echo "<td>" . $record[$ele] . "</td>";
            }

            $deleteUrl = Yii::app()->createAbsoluteUrl("tagSettings/deleteTagQueued", array('id' => $qTag->tagID));

            echo '<td>
                    <a  data-icon-only="true" 
                    href="' . $deleteUrl . '"
                    data-icon-primary="ui-icon-trash" 
                    class="button ui-button ui-widget ui-state-error ui-corner-all ui-button-text-icon-primary ui-button-icon-only" role="button">
                    <span class="ui-button-icon-primary ui-icon ui-icon-trash"></span>
                    <span class="ui-button-text"><span class="ui-button-icon-primary ui-icon ui-icon-trash"></span>
                    <span class="ui-button-text">Delete Tag&nbsp;</span></span></a>
                    <a  data-icon-only="true" 
                    href="' . $editUrl . '"
                    data-icon-primary="ui-icon-pencil" 
                    class="button ui-button ui-widget ui-state-success ui-corner-all ui-button-text-icon-primary ui-button-icon-only" role="button">
                    <span class="ui-button-icon-primary ui-icon ui-icon-pencil"></span>
                    <span class="ui-button-text"><span class="ui-button-icon-primary ui-icon ui-icon-pencil"></span>
                    <span class="ui-button-text">Edit tag&nbsp;</span></span></a>
                    <a  data-icon-only="true"
                        href="' . $stopurl . '"
                        data-icon-primary="ui-icon-stop" 
                        class="button ui-button ui-widget ui-state-highlight ui-corner-all ui-button-text-icon-primary ui-button-icon-only" role="button">
                        <span class="ui-button-icon-primary ui-icon ui-icon-stop"></span>
                        <span class="ui-button-text"><span class="ui-button-icon-primary ui-icon ui-icon-stop"></span>
                        <span class="ui-button-text" >STOP Tag &nbsp;</span></span></a>';
            echo '</td>';
            echo '</tr>';
        }//foreach
    }

    public static function getTripInfo($tagId) {

        $tripInfoAr = array();

        if (0)
            echo "getTripInfo $tagId<br/>";
        if (!isset($tagId)) {
            //echo "Problem loading Tag Settings\n<br/>";
        }

        $spTolChk = "select * from wcl_truckInfo where w_tripID IN (select DISTINCT wcl_map_trId from wcl_truckTagMap where wcl_map_tagId= '{$tagId}')";
        $tcommand = Yii::app()->db->createCommand($spTolChk)->queryRow();

        if (0)
            echo $spTolChk;

        if (count(tcommand) > 0) {
            $tripInfoAr = $tcommand;
        }

        return $tripInfoAr;
    }

    public static function initDataTable() {
        ?>

        <?php

    }

}

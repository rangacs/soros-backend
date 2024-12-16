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
class TagGroupHelper {
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
    public static function renderTagtable($tagObject, $elements) {

        $tagsArray = $tagObject->getAllTags();
        //$tagHeadeEle = array_merge(array("Tag Name", 'Tag Group','Calib','Start Time','End Time'), $elements);
        $tagHeadeEle = array_merge(array("Tag Name",  'Start Time', 'End Time'), $elements);

        $completeTags = $tagsArray['completedTags'];



        echo '<table id="tag-group-view" class="full margin-top-20 list-table">';

        self::renderHeader($tagHeadeEle);

        self::renderBody($completeTags, $elements);
        //self::renderFooter($completeTags,$elements);
        echo '</table>';
    }

    public static function renderHeader($elements) {

        echo '<thead><tr>';

        echo '<th class="ui-state-default ui-corner-top"></th>';
        foreach ($elements as $ele) {

            if ($ele == "BTU")
                $ele = "GCV";
            echo '<th class="ui-state-default ui-corner-top">' . Yii::t('app', $ele) . '</th>';
        }

        echo '<th class="ui-state-default ui-corer-top">' . Yii::t('app', 'Action') . '</th>';

        echo '</tr></thead>';
    }

    public static function renderFooter($tags, $elements) {


        echo '<tfooter>';
        echo '<tr>';

        echo "<th></th>";
        echo "<th class='ui-state-default ui-corner-top'>Average</th>";
        echo "<th class='ui-state-default ui-corner-top'></th>";
        echo "<th class='ui-state-default ui-corner-top'></th>";
        echo "<th class='ui-state-default ui-corner-top'></th>";
        echo "<th class='ui-state-default ui-corner-top'></th>";
        foreach ($elements as $ele) {
            $array = LabUtility::getColumn($tags, $ele);
            $avg = round(array_sum($array) / count($array), 3);
            echo '<th class="ui-state-default ui-corner-top">' . Yii::t('app', $avg) . '</th>';
        }

        echo '<th class="ui-state-default ui-corer-top"></th>';

        echo '</tr>';
        echo '<tr>';

        echo "<th class='ui-state-default ui-corner-top'>Elements</th>";
        echo "<th class='ui-state-default ui-corner-top'></th>";
        echo "<th class='ui-state-default ui-corner-top'></th>";
        echo "<th class='ui-state-default ui-corner-top'></th>";
        echo "<th class='ui-state-default ui-corner-top'></th>";
        foreach ($elements as $ele) {

            echo '<th class="ui-state-default ui-corner-top">' . Yii::t('app', $ele) . '</th>';
        }

        echo '<th class="ui-state-default ui-corer-top"></th>';

        echo '</tr>';
        echo '</tfooter>';
    }

    public static function renderBody($data, $elements) {

        $dateTimeFormat = RmSettings::getValueFromKey("GLOBAL_TIME_FORMAT", 'Y-m-d H:i:s');
        if (count($data) > 0) {

            foreach ($data as $row) {

                $interval = new IntervalObject();

                $interval->setStartTime($row['LocalstartTime']);
                $interval->setEndTime($row['LocalendTime']);
                //setStartTime
                //setEndTime
                $analysisDataProvider = new AnalysisDataProvider();
                $analysisDataProvider->setElements($elements);

                $tagObject = TagCompleted::model()->findByPk($row['tagID']);
                $record = $analysisDataProvider->getTagAvg($tagObject);


                $viewurl = Yii::app()->createAbsoluteUrl('tagSettings/tagView', array('id' => $row['tagID'], 'type' => 'completed'));

//                $tripInfo = TagQueuedHelper::getTripInfo($row['tagID']);

                $query = "select count(tagID)  as count  from rta_tag_index_sub_tag where tagID='" . $row['tagID'] . "'";

//                echo $query;    
                $result = Yii::app()->db->createCommand($query)->queryScalar();
                if ($result > 1) {
                    $subView = '<span style="display:table-cell" class="ui-button-icon-primary ui-icon ui-icon-circle-plus" onclick="getSubTag(' . $row['tagID'] . ')"></span>';


                    $checkView = '';
                } else {
                    $subView = "";
                    $checkView = '<input name="tag_checkbox" value="' . $row['tagID'] . '" type="checkbox">';
                }
//                $subView = '<span class="ui-button-icon-primary ui-icon ui-icon-circle-plus" onclick="getSubTag(' . $row['tagID'] . ')"></span>';
                echo '<tr>';

                echo '<td>' . $checkView . '</td>';
                echo "<td><div style='display:table'>" . $subView . "  <a style='display:table-cell' href='" . $viewurl . "' style='color:blue;'>" . $row['tagName'] . "</a></div></td>";
//                echo "<td> " . TagGroupHelper::getNameFromID($row['tagGroupID']) . "</td>";
//                echo "<td> " . WclRfidCalMap::findActiveCalibFile($tripInfo["w_matCode"]) . "</td>";
                echo "<td> " . date($dateTimeFormat, strtotime($row['LocalstartTime'])) . "</td>";
                echo "<td> " . date($dateTimeFormat, strtotime($row['LocalendTime'])) . "</td>";
                foreach ($elements as $ele) {

                    //echo "<th>" . $record[$ele] . "</th>";
                    echo "<th>" . $record[$ele] . "</th>";
                }


                $deleteUrl = Yii::app()->createAbsoluteUrl("tagSettings/deleteTagCompleted", array('id' => $row['tagID']));

                echo '<td>
                            <a  data-icon-only="true" 
                            href="' . $deleteUrl . '"
                            data-icon-primary="ui-icon-trash" 
                            class="button ui-button ui-widget ui-state-error ui-corner-all ui-button-text-icon-primary ui-button-icon-only" role="button">
                            <span class="ui-button-icon-primary ui-icon ui-icon-trash"></span>
                            <span class="ui-button-text"><span class="ui-button-icon-primary ui-icon ui-icon-trash"></span>
                            <span class="ui-button-text">Delete Tag&nbsp;</span></span></a>
                        </td>';
                echo '</tr>';
            }//foreach
        }//if
        else {
            echo '<tr><td colpsan=4>No Tags Present</td></tr>';
        }//else
    }

    public static function renderGroupView($tgObj) {
        var_dump($tgObj);
    }

    public static function getNameFromID($tagGroupID) {

        $tagGroupModel = TagGroup::model()->find('tagGroupID = :tgID', array(':tgID' => $tagGroupID));

        if ($tagGroupModel) {

            return $tagGroupModel->tagGroupName;
        } else {
            return '';
        }
    }

}

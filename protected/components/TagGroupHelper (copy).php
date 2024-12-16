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

        $tagHeadeEle = array_merge(array("Tag Name", 'Tag Group','Start Time','End Time'), $elements);

        $completeTags = $tagsArray['completedTags'];

        echo '<table class="full margin-top-20 list-table">';

        self::renderHeader($tagHeadeEle);

        self::renderBody($completeTags, $elements);

        echo '</table>';

        self::initDataTable();
    }

    public static function renderHeader($elements) {

        echo '<thead><tr>';


        foreach ($elements as $ele) {

            echo '<th class="ui-state-default ui-corner-top">' . Yii::t('app', $ele) . '</th>';
        }

        echo '<th class="ui-state-default ui-corer-top">' . Yii::t('app', 'Action') . '</th>';

        echo '</tr></thead>';
    }

    public static function renderBody($data, $elements) {
        
            $dateTimeFormat = RmSettings::getValueFromKey("GLOBAL_TIME_FORMAT", 'Y-m-d H:i:s');
        if(count($data) > 0) {

            foreach ($data as $row) {

                $interval = new IntervalObject();

                $interval->setStartTime($row['LocalstartTime']);
                $interval->setEndTime($row['LocalendTime']);
                //setStartTime
                //setEndTime
                $analysisDataProvider = new AnalysisDataProvider();
                $analysisDataProvider->setElements($elements);

                //$record = $analysisDataProvider->getIntervalAvg($interval);

                $viewurl = Yii::app()->createAbsoluteUrl('tagSettings/tagView', array('id' => $row['tagID'], 'type' => 'completed'));
                echo '<tr>';

                echo "<td> <a href='" . $viewurl . "' style='color:blue;'>" . $row['tagName'] . "</a></td>";
                echo "<td> " . TagGroupHelper::getNameFromID($row['tagGroupID']) . "</td>";
                echo "<td> " .date($dateTimeFormat,strtotime( $row['LocalstartTime'] )). "</td>";
                echo "<td> " . date($dateTimeFormat,strtotime($row['LocalendTime'])) . "</td>";
                foreach ($elements as $ele) {

                    //echo "<th>" . $record[$ele] . "</th>";
                    echo "<th>" . $row[$ele] . "</th>";
                }


                $deleteUrl = Yii::app()->createAbsoluteUrl("tagSettings/deleteTagCompleted", array('id' => $row['tagID']));

                echo '<td>
                            <a  data-icon-only="true" 
                            href="' . $deleteUrl . '"
                            data-icon-primary="ui-icon-trash" 
                            class="button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary ui-button-icon-only" role="button">
                            <span class="ui-button-icon-primary ui-icon ui-icon-trash"></span>
                            <span class="ui-button-text"><span class="ui-button-icon-primary ui-icon ui-icon-trash"></span>
                            <span class="ui-button-text">Delete Tag&nbsp;</span></span></a>
                        </td>';
                echo '</tr>';
            }//foreach
        }//if
        else {
            echo '<tr><td colpsan=3>No Tags Present</td></tr>';
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

    public static function initDataTable() {

    }

}

<?php

/**
 * LogicalHelper is a logical helper class.
 * It helps deciding View based logical handling of data/screen.
 */
class LogicalHelper {
    /*
      Static function to determine the default page layout.
     *  1) Login Page is userScreen
     *  2) Admin/Management Screen are authScreen
     *  3) All static pages are otherwise
     */

    public static function yiiGetDefaultPage() {
        
        $controllerId = strtolower(Yii::app()->getController()->id);
        $userScreen  = Yii::app()->params["userScreen"];
        $authScreen = Yii::app()->params["authScreen"];
        if (in_array($controllerId, $userScreen, true)) {
            if (Yii::app()->user->isGuest)
                return "loginScreen.php";
            else {
                return "authScreen.php";
            }
        }
        else if (in_array($controllerId, $authScreen, true) && Yii::app()->user->name) {
            return "authScreen.php";
        } else {
            return "staticScreen.php";
        }
    }

    public static function osWrapper($str) {
        if (stristr(PHP_OS, 'WIN')) {
            $str = str_replace("/", "\\", $str);
        } else {
            $str = str_replace("\\", "/", $str);
        }
        return $str;
    }

    /**
     * 
     * @param type $db
     * @return array
     */
    public static function getColumnPreferenceArray($db) {

        try {

            


            return true;
        } catch (Exception $e) {
            echo "Was not able to fetch the tables from db.";
            return false;
        }
    }

    
    public static function getAnalysisTables($db) {

        try {

            $output_arr = array();
            $query = "SHOW TABLES FROM {$db} like '%analysis%'";
            $command = Yii::app()->db->createCommand($query);
            $rs = $command->queryAll();

            foreach ($rs as $k1 => $v1) {
                foreach ($v1 as $k2 => $v2) {
                    $output_arr[$v2] = $v2;
                }
            }



            return $output_arr;
        } catch (Exception $e) {
            echo "Was not able to fetch the tables from db.";
            return false;
        }
    }

}

?>
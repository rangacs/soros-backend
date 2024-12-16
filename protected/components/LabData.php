<?php
/**
 * Description of LabData
 *
 * @author webtatva
 */
class LabData {

    //put your code here

    public $db;
    public $tablename;
    public $column;
    public $data = array();
    public $startTime;
    public $endTime;

    public function __construct() {

        //TO enable query buffering 
        $ignoreColums = array();
        $this->tablename = "lab_data";
        $this->db = Yii::app()->db;
        $colQuery = "show columns from  " . $this->tablename;
        $colCommand = $this->db->createCommand($colQuery);
        $colArray = $colCommand->queryAll();
        $elemts = array();
        foreach ($colArray as $item) {

            if (!in_array($item['Field'], $ignoreColums)) {
                $elemts[] = $item['Field'];
            }
        }
        $this->column = $colArray;
    }
    public function init(){
        
    }
    
    private function getTimeLimit(){
       // $this->getTimeLimit();

        $minQuery   = "select min(end_time)  as start_time from ".$this->tablename;
        $maxQuery   = "select max(end_time)  as end_time from ".$this->tablename;
        
        $minCommand =   $this->db->createCommand($minQuery);
        $minResult  =   $minCommand->queryRow();
        
        $this->startTime =  $minResult['start_time'];
        
        $maxCommand =   $this->db->createCommand($maxQuery);
        $maxResult  =   $maxCommand->queryRow();
        
        $this->endTime  = $maxResult['end_time'];
    }

    public function takeBackUp() {

        $labDataHistory = new LabDataHistory();
        $query      = "select * from lab_data";
        $count      = "select count(lab_data_id) as count from lab_data";
        $countCommand   =  Yii::app()->db->createCommand($count);
        $countResult    =  $countCommand->queryRow();
        $totalRecords   = $countResult['count'];
        
        //Exit if no data present
        if($totalRecords <= 0){
            return false;
        }
  
        $command    = Yii::$app->db->createCommand($query);
        $data       = $command->queryAll();
        
        //Back up directory is in application/app/web/upload
        $uploaddir  = Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR ."uploads".DIRECTORY_SEPARATOR."labDataBackUp";

        
        $fileName   = "labData_".strtotime($this->startTime)."_".strtotime($this->endTime);
        $fp         = fopen($uploaddir.DIRECTORY_SEPARATOR.$fileName, 'w');

        foreach ($data as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);
        
        $temp_fileName   = "labTemplate_".strtotime($this->startTime)."_".strtotime($this->endTime);
        $tfp         = fopen($uploaddir.DIRECTORY_SEPARATOR.$temp_fileName, 'w');
        
        //Write content of lab_data table  to csv file
        $data = $this->getTableStructure();
        foreach ($data as $fields) {
            fputcsv($tfp, $fields);
        }
        fclose($tfp);
        
        $data =  file_get_contents($uploaddir.DIRECTORY_SEPARATOR.$fileName, FILE_USE_INCLUDE_PATH);
        $template = file_get_contents($uploaddir.DIRECTORY_SEPARATOR.$temp_fileName);
        
        
        
        unlink($uploaddir.DIRECTORY_SEPARATOR.$fileName, FILE_USE_INCLUDE_PATH);
        unlink($uploaddir.DIRECTORY_SEPARATOR.$temp_fileName);
      //  $labDataHistory->insertRecord($this->startTime,$this->endTime,$data,$template);
        
        
        $activeTemplate = LabTemplate::findOne(array('status' => 1));
        
        $labDataHistoryModal = new LabDataHistory();
        
        $labDataHistoryModal->data = $data;
        $labDataHistoryModal->template = $activeTemplate->template_id;
        $labDataHistoryModal->upload_time = date('Y-m-d H:i:s',time());
        $labDataHistoryModal->uploaded_by = Yii::$app->user->id;
        $labDataHistoryModal->start_time =  date('Y-m-d H:i:s',time());//'';$this->startTime;
        $labDataHistoryModal->end_time   =  date('Y-m-d H:i:s',time());//$this->endTime;
        $labDataHistoryModal->uploaded_by = Yii::$app->user->id;
        $labDataHistoryModal->save();
        
        $error = $labDataHistoryModal->errors;
        

        //Delete Previous records 
    }
    
    
    public function getTableStructure(){
        
        
        $query      = "show columns from  lab_data";
        $count      = "select count(lab_data_id) as count from lab_data";
        $countCommand   =  Yii::$app->db->createCommand($count);
        $countResult    =  $countCommand->queryOne();
        $totalRecords   = $countResult['count'];
        
        //Exit if no data present
        if($totalRecords <= 0){
            return false;
        }
        
        $command    = Yii::$app->db->createCommand($query);
        $data       = $command->queryAll();
        return $data;
        //$labDataHistory->insertRecord($this->startTime,$this->endTime,$fileName);
        
        
        
    }
    
    public function parseDataFile($filePath) {

        $table = array();
        $eleData = array();
        $row = 1;
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {



                //Getting eement data

                foreach ($data as $key => $value) {

                    $eleData[$row][] = "" . $value . ""; // '`' . $value . '`'; 
                }
                $row++;
            }
            fclose($handle);
        }
        $table = $eleData;
        //$table['ele_data'] = $eleData;
        return $table;
    }

    public function parseTemplatefile($filePath) {

        $table = array();
        $row = 0;
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                $cName = preg_replace('/\s+/', '', $data[0]);
                $table[$row] = $cName;
                //echo "<p> $num fields in line $row: <br /></p>\n";
                $row++;
            }
            fclose($handle);
        }

        return $table;
    }

    private function buildInsertDataQuery($data) {
        $elements = $data['elements'];
        $values = $data['ele_data'];
        $eleSubQuery = "INSERT INTO   `lab_data` (" . implode(',', $elements) . ")";

        $valuesString = array();
        foreach ($values as $item) {
            $count = count($item);


            $tmpQuery = "(" . implode(', ', $item) . ")";
            //Filtring emply line
            if (strlen($tmpQuery) <= ($count + 2)) {
                continue;
            }
            $valuesString[] = $tmpQuery;
        }



        $valuesQuery = implode(", ", $valuesString);

        $insertQuery = $eleSubQuery . " VALUES " . $valuesQuery;

        return $insertQuery;
    }
    
       private function clean($string) {
   //$string = str_replace('', '-', $string); // Replaces all spaces with hyphens.
   return preg_replace('/[^A-Za-z0-9\-\.]/', '', $string); // Removes special chars.
}

}

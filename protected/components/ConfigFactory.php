<?php


/**
 * Description of ConfigFactory
 *
 * @author webtatva
 */
class ConfigFactory {

    //put your code here


    public $fileName = NULL;
    public $fileContent = NULL;
    public $directories = array();
    public $lines = array();
    public $lookUpArray = array();
    public $dataEntries = array();

    function __construct($filename) {
        $this->fileName = $filename;
        $this->lines = file($filename);
        $this->fileContent = file_get_contents($filename);

        $this->directories = $this->getDirectories();
        $this->buidLookUpArray();
        $this->buildDataEntries();
    }

    function loadFile() {
        
    }

    private function getDirectories() {


        $dirPattern = "/[\[][a-zA-Z]+[-_]*\w*[\]]/";
        $matches_out = array();
        $directories = array();


        foreach ($this->lines as $key => $line) {

            $sub = substr($line, 0, 1);

            //Skipping comment
            if ($sub == '#') {
                continue;
            }
            preg_match_all($dirPattern, $line, $matches_out);

            if (!empty($matches_out[0][0]))
                $directories[] = $matches_out[0][0];
        }

        return $directories;
    }

    public function getDirNames() {

        $dirNames = array();

        foreach ($this->directories as $dir) {


            $dirNames[] = $this->clean($dir);
        }


        return $dirNames;
    }

    function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

        return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }

    private function buidLookUpArray() {

        $lookUp = array();
//var_dump($matches_out);


        $index = 0;
        foreach ($this->directories as $key => $path) {




            if ($line = $this->getLineWithString($path)) {


                $lookUp[$index]['line'] = $line;
                $lookUp[$index]['path'] = $path;

                $index++;
            }
        }//End of foreach

        $this->lookUpArray = $lookUp;
    }

    function buildDataEntries() {
        $dictionary = array();
        foreach ($this->lookUpArray as $key => $value) {

            $begin = $value['line'];

            if (isset($this->lookUpArray[$key + 1])) {
                $end = $this->lookUpArray[$key + 1]['line'];
            } else {

                $end = count($this->lines) - 1;
            }

            $index = $value['path'];
            $dictionary[$index] = $this->getKeyValues($begin, $end);
        }



        $this->dataEntries = $dictionary;
    }

    function getLineWithString($str) {

        foreach ($this->lines as $lineNumber => $line) {
            $sub = substr($line, 0, 1);

            //Skipping comment
            if ($sub == '#') {
                continue;
            }
            if (strpos($line, $str) !== false) {


                return $lineNumber;
            }
        }

        return false;
    }

    function getEditForm($directory) {

        $key = "[" . $directory . "]";

        $entries = $this->dataEntries[$key];



        $form_title = "<div class='form col-md-6 '>"
                . "<h4 style='margin:20px'>Section:<i>" . $directory . "</i></h4>"
                . "<form class='form-horizontal' id='{$directory}' action='index.php' method='post'> ";
        $form_body = '';

        foreach ($entries as $key => $value) {

            $form_group = '<div class="form-group">
                                            <label for="name" class="control-label col-md-3">' . $key . '</label>
                                            <div class="col-md-9">
                                             <input type="text" name="' . $directory . '[' . $key . ']" value="' . $value . '" class="form-control">
                                            </div>
                                          </div>';
            $row = '<tr> <th>' . $key . '</th>
                                <td> 
                                    <div class="label" style="display: none;">' . $value . '                                   </div>
                                    <div class="edit"><input type="text" name="' . $directory . '[' . $key . ']" value="' . $value . '" class="form-control"> 
                                    </div>
                                </td>
                            </tr>';
            $input = "<input type='text' name='" . $key . "' value='" . $value . "'/>";

            $form_body .= $form_group;
        }
        $form_end = "</form></div>";

        return $form_title . $form_body . $form_end;
        //echo $form_body;
        //echo $form_end;        
    }

    function getKeyValues($begin, $end) {

        $map = array();

        for ($i = $begin + 1; $i < $end; $i++) {

            $line = $this->lines[$i];
            $sub = substr($line, 0, 1);

            //Skipping comment
            if ($sub == '#') {
                continue;
            }

            $keyValue = explode("=", $line);


            if (!isset($keyValue[1])) {
                $keyValue[1] = NULL;
            }


            $map[trim($keyValue[0])] = trim($keyValue[1]);
        }

        return $map;
    }

}

function cmp($a, $b) {
    if (count($a) == count($b)) {
        return 0;
    }
    return (count($a) < count($b)) ? -1 : 1;
}

function emptyArray($a) {

    $count = count($a);

    if ($count <= 0) {
        return false;
    } else {
        return true;
    }
}

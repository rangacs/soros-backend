<style type="text/css">   
    table {  
        border-collapse: collapse;  
    }  
        .inline{   
            display: inline-block;   
            float: right;   
            margin: 20px 0px;   
        }   
         
        input, button{   
            height: 34px;   
        }   
  
    .pagination {   
        display: inline-block;   
    }   
    .pagination a {   
        font-weight:bold;   
        font-size:18px;   
        color: black;   
        float: left;   
        padding: 8px 16px;   
        text-decoration: none;   
        border:1px solid black;   
    }   
    .pagination a.active {   
            background-color: pink;   
    }   
    .pagination a:hover:not(.active) {   
        background-color: skyblue;   
    }   
</style> 

<div class="pagination">    
      <?php  
     
		$cur_Url = $_SERVER['REQUEST_URI'];
		$stripedUrl = preg_split("/page/", $cur_Url, -1, PREG_SPLIT_DELIM_CAPTURE);
		$stripedUrl = $stripedUrl[0];
		$stripedUrl = preg_split("/index/", $cur_Url, -1, PREG_SPLIT_DELIM_CAPTURE);
		$stripedUrl = $stripedUrl[0] . "/index/";
		$stripedUrl = str_replace("//","/",$stripedUrl);
		$stripedUrl = str_replace("//","/",$stripedUrl);
		
        $total_records = getCompletedTagsCount();
		$results_per_page = 20;
        if (isset($_REQUEST["page"])) {    
            $page  = $_REQUEST["page"];    
        }    
        else {    
          $page=1;    
        }
		
		echo "<br/>";     
        // Number of pages required.   
        $total_pages = ceil($total_records / $results_per_page);     
        $pagLink = "";       
      
        if($page>=2){   
            echo "<a href='".$stripedUrl."/page/".($page-1)."'>  Prev </a>";   
        }       
                   
        for ($i=1; $i<=$total_pages; $i++) {   
          if ($i == $page) {   
              $pagLink .= "<a class = 'active' href='".$stripedUrl."/page/".$i."'>".$i." </a>";   
          }               
          else  {   
              $pagLink .= "<a href='".$stripedUrl."/page/".$i."'>".$i." </a>";     
          }   
        };     
        echo $pagLink;   
  
        if($page<$total_pages){   
            echo "<a href='".$stripedUrl."/page/".($page+1)."'>  Next </a>";   
        }   
  
      ?>    
      </div>  
  
  
      <div class="inline">   
      <input id="page" type="number" min="1" max="<?php echo $total_pages?>"   
      placeholder="<?php echo $page."/".$total_pages; ?>" required>   
      <button onClick="go2Page();">Go</button>   
     </div>    
    </div>   
  </div>  
</center>   
  <script>   
    function go2Page()   
    {   
        var page = document.getElementById("page").value;   
        page = ((page><?php echo $total_pages; ?>)?<?php echo $total_pages; ?>:((page<1)?1:page));  
		document.getElementById("pageHid").value = page;
		
		document.getElementById("tag-queued-form").submit();
        //window.location.href = "<?php echo $stripedUrl; ?>" + '/page/'+page;   
    }   
  </script>  

<?php

    function getCompletedTagsCount() {
		global $selectedGroup;
		
        if (!isset ($_REQUEST['page']) ) {  
            $page = 1;  
        } else {  
            $page = $_REQUEST['page'];  
        }
        $results_per_page = 20;  
        $page_first_result = ($page-1) * $results_per_page;  
        
		$defTagGrpId = RmSettings::getValueFromKey("RFID_TAGGRP_ID", 103);
		$tagGroupID = isset($_REQUEST['TagGroup']['tagGroupID']) ? $_REQUEST['TagGroup']['tagGroupID'] : $defTagGrpId;

        $tagNameOnly = isset($_POST['search_name_only']) ? isset($_POST['search_name_only']) : false;
  

        if ($tagNameOnly) {

            $tagName = isset($_POST['tagGroup_tagName']) ? $_POST['tagGroup_tagName'] : "";

            $query = "select * from rta_tag_index_completed where tagName LIKE '%" . $tagName . "%' and tagGroupID = $tagGroupID   ORDER BY LocalendTime DESC";
        } else if (isset($_POST['tagGroup_startDate']) && $_POST['tagGroup_endDate']) {
            $strtDateStirng = $_POST["tagGroup_startDate"] . " " . $_POST["tagGroup_startTime"];
            $endDateString = $_POST["tagGroup_endDate"] . " " . $_POST["tagGroup_endTime"];
            $startDateTime = date('Y-m-d H:i:s', strtotime($strtDateStirng));
            $endDateTime = date('Y-m-d H:i:s', strtotime($endDateString));
            $tagGroupID = isset($_POST['tagGroup_tagGroupID']) ? $_POST['tagGroup_tagGroupID'] : $tagGroupID;
            $tagName = isset($_POST['tagGroup_tagName']) ? $_POST['tagGroup_tagName'] : "";

            $query = "select * from rta_tag_index_completed where tagName LIKE '%" . $tagName . "%' and tagGroupID = $tagGroupID  and LocalendTime >'$startDateTime' AND LocalendTIme < '$endDateTime' ORDER BY LocalendTime DESC";
        } else {

            $query = "select * from rta_tag_index_completed where tagGroupID = $tagGroupID ORDER BY LocalendTime DESC";
        }

        $results = Yii::app()->db->createCommand($query)->queryAll();
        
        //AB82321
        $number_of_result = count($results);
		return ($number_of_result);
        //$number_of_page = ceil ($number_of_result / $results_per_page);  
        //$limitAdditionQry = " LIMIT " . $page_first_result . ',' . $results_per_page;
		//
        //$results = Yii::app()->db->createCommand($query . $limitAdditionQry)->queryAll();        
		//
        //return $results;
    }//getCompletedTags

?>
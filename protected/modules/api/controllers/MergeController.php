<?php

class MergeController extends BaseController {

    public function actionMerge() {
           

        $mergTagList = $this->request;

	$mergTagList  = (json_decode(file_get_contents('php://input'), true));
     
        if(isset($mergTagList['completed']) && isset($mergTagList['queued'])){


            $tagList =  $this->mergeArray($mergTagList['completed'], $mergTagList['queued']);



        }else if(isset ($mergTagList['completed'])){
            $tagList = $mergTagList['completed'];
        }
        else if(isset ($mergTagList['queued'])){
            $tagList = $mergTagList['queued'];
        }
        
        if(count($tagList) == 0){
           $this->sendFailedResponse(array('data' => $tagList )); 
        }
        
	$tagGroupID = $this->getTagGroupID($mergTagList['completed']);


	/* ======================Find Start and end date=========================== */

	       foreach ($tagList as $tagId) {

            $tagModel = TagGroup::getTagById($tagId);


            if (!$tagModel) {
                continue;
            }

            $timeSeries[] = strtotime($tagModel->LocalendTime);
            $timeSeries[] = strtotime($tagModel->LocalstartTime);
        }

        $minTime = min($timeSeries);
        $maxTime = max($timeSeries);


	/* ================================================= */



        $newTagName = $mergTagList['mergeName'];

        if (count($mergTagList['queued'])) {
            $newTag = new TagQueued();
            $newTag->status = 'queued';
	    //$newTag->hasMerge = 'true';
        } else {
            $newTag = new TagCompleted();
            $newTag->status = 'completed';
            $newTag->goodDataSecondsWeight = 1;
            $newTag->massflowWeight = 1;
            $newTag->hasMerge = 'true';
        }
        $newTag->tagName = $newTagName;
	$newTag->LocalstartTime  = date("Y-m-d H:i:s",$minTime);
        $newTag->LocalendTime = date("Y-m-d H:i:s",$maxTime);
;
          
       

        $newTag->rtaMasterID = 15;
        $newTag->tagGroupID = $tagGroupID;

        if ($newTag->save()) {
//            echo "New Tag is created added";
        } else {
            $this->sendFailedResponse(array('data' => $newTag->errors));
        }
        $timeSeries = array();

        foreach ($tagList as $tagId) {

            $tagModel = TagGroup::getTagById($tagId);

            $newTag->tagGroupID = $tagModel->tagGroupID;

            if (!$tagModel) {
                continue;
            }


            $subTag = new SubTag();
            $subTag->tagID = $newTag->tagID;
            $subTag->tagName = $tagModel->tagName;
            $subTag->LocalendTime = $tagModel->LocalendTime;
            $subTag->LocalstartTime = $tagModel->LocalstartTime;
            $subTag->status = $newTag->status;
            $subTag->rtaMasterID = $tagModel->rtaMasterID;
            $subTag->tagGroupID = $tagModel->tagGroupID;

            if ($subTag->save()) {
                $tagModel->delete(); //TODO
            } else {
                var_dump($subTag->errors);
            }
        }




        if (count($mergTagList['queued']) == 0 ) {

//            echo "Finding average";
            $average = TagHelper::getTagAvg($newTag);
//            echo "Calculated average";
//            var_dump($average);
            foreach ($average as $key => $value) {

                try {
                    $newTag->$key = $value;
                } catch (Exception $ex) {

//                    echo "Some exception while handling =>" . $key;
                }
            }
        }
        $newTag->save();
        $this->sendSuccessResponse(array('data' => 'Tag are merged successfully'));
    }

    public function mergeArray($array1, $array2) {
	$marray = array();
        if (!empty($array1)) {

	

            foreach ($array1 as $aele) {

                $marray [] = $aele;
            }
        }
        if (!empty($array2)) {
        
            foreach ($array2 as $aele) {
                $marray [] = $aele;
            }
        }
        return $marray;
    }

    public function getTagGroupID($tagIds) {

        $id = array_pop($tagIds);

        $tagModel = TagGroup::getTagById($id);

        return $tagModel->tagGroupID;
    }

}

<table class="list-table">
    <thead>
        <tr>
    <th>TagName</th>
    <th>Start Time</th>
    <th>End Time</th>
    <th>Satus</th>
    </tr>
    </thead>
    <tbody>
        <?php 


        foreach($tagList as $tag){
            echo "<tr>";
            echo "<td>".$tag->tagName."</td>";
            echo "<td>".$tag->LocalstartTime."</td>";
            echo "<td>".$tag->LocalendTime."</td>";
            echo "<td>".$tag->status."</td>";
        }
//var_dump($tagList);

?>
    </tbody>
    <tfoot>
    <th>New Tag Name</th>
    <th><input type="text" name="merged_tag_name" id="merged_tag_name"/></th>
    <th> <button onclick="mergetags()"> Merge </button></th>
    </tfoot>
</table>

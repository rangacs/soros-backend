<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//var_dump($model);


echo '<tr>';

//echo "<td> ".."</td>";
foreach ($displayElement  as $value){
    
    
    echo  "<td>".$data[$value]."</td>";
}

?>

<td> <button class="btn btn-danger delete-btn" data-time="<?php echo $data['EndTime']?>"><i class="fa fa-trash"> </i></button></td>
<?php 
echo '</tr>';

?>
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<!-- Modal -->
<div class="modal fade" id="example-template" tabindex="-1" role="dialog" aria-labelledby="example-template">
  <div class="modal-dialog modal-full" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> <?php Yii::t('app','Example template');?></h4>
      </div>
      <div class="modal-body">
          <table class="table table-blue">
              <colgroup><col width="109"/><col width="75"/><col width="102"/><col width="102"/><col width="98"/><col width="69"/><col width="69"/><col width="73"/><col width="77"/><col width="69"/><col width="77"/><col width="77"/><col width="77"/><col width="77"/><col width="93"/></colgroup><tr class="ro1"><td style="text-align:left;width:70.44pt; " class="Default"><p>HeadingRow</p></td><td style="text-align:left;width:48.84pt; " class="Default"><p>Sample I</p></td><td style="text-align:left;width:65.85pt; " class="Default"><p>Start Time</p></td><td style="text-align:left;width:65.85pt; " class="Default"><p>End Time</p></td><td style="text-align:left;width:63.5pt; " class="Default"><p>StandardCal</p></td><td style="text-align:left;width:45.01pt; " class="Default"><p>Sulfur</p></td><td style="text-align:left;width:45.01pt; " class="Default"><p>Ash</p></td><td style="text-align:left;width:47.31pt; " class="Default"><p>Moisture</p></td><td style="text-align:left;width:49.66pt; " class="Default"><p>SiO2</p></td><td style="text-align:left;width:45.01pt; " class="Default"><p>CaO</p></td><td style="text-align:left;width:49.66pt; " class="Default"><p>Fe2O3</p></td><td style="text-align:left;width:49.66pt; " class="Default"><p>Al2O3</p></td><td style="text-align:left;width:49.66pt; " class="Default"><p>TiO2</p></td><td style="text-align:left;width:49.66pt; " class="Default"><p>K2O</p></td><td style="text-align:left;width:60.46pt; " class="Default"><p>MAFBTU</p></td></tr><tr class="ro1"><td style="text-align:left;width:70.44pt; " class="Default"><p>DataTypeRow</p></td><td style="text-align:left;width:48.84pt; " class="Default"><p>Name</p></td><td style="text-align:left;width:65.85pt; " class="Default"><p>datetime</p></td><td style="text-align:left;width:65.85pt; " class="Default"><p>datetime</p></td><td style="text-align:left;width:63.5pt; " class="Default"><p>Text</p></td><td style="text-align:left;width:45.01pt; " class="Default"><p>Decimal</p></td><td style="text-align:left;width:45.01pt; " class="Default"><p>Decimal</p></td><td style="text-align:left;width:47.31pt; " class="Default"><p>Decimal</p></td><td style="text-align:left;width:49.66pt; " class="Default"><p>Decimal</p></td><td style="text-align:left;width:45.01pt; " class="Default"><p>Decimal</p></td><td style="text-align:left;width:49.66pt; " class="Default"><p>Decimal</p></td><td style="text-align:left;width:49.66pt; " class="Default"><p>Decimal</p></td><td style="text-align:left;width:49.66pt; " class="Default"><p>Decimal</p></td><td style="text-align:left;width:49.66pt; " class="Default"><p>Decimal</p></td><td style="text-align:left;width:60.46pt; " class="Default"><p>Decimal</p></td></tr><tr class="ro1"><td style="text-align:left;width:70.44pt; " class="Default"><p>ValuesRow</p></td><td style="text-align:left;width:48.84pt; " class="Default"><p>CHYCO219</p></td><td style="text-align:right; width:65.85pt; " class="Default"><p>41466.41266</p></td><td style="text-align:right; width:65.85pt; " class="Default"><p>41466.47225</p></td><td style="text-align:left;width:63.5pt; " class="Default"><p>coal</p></td><td style="text-align:right; width:45.01pt; " class="Default"><p>1.5</p></td><td style="text-align:right; width:45.01pt; " class="Default"><p>7.18</p></td><td style="text-align:right; width:47.31pt; " class="Default"><p>6.48</p></td><td style="text-align:right; width:49.66pt; " class="Default"><p>3.671852</p></td><td style="text-align:right; width:45.01pt; " class="Default"><p>0.19027</p></td><td style="text-align:right; width:49.66pt; " class="Default"><p>1.136594</p></td><td style="text-align:right; width:49.66pt; " class="Default"><p>1.782794</p></td><td style="text-align:right; width:49.66pt; " class="Default"><p>0.067492</p></td><td style="text-align:right; width:49.66pt; " class="Default"><p>0.130676</p></td><td style="text-align:right; width:60.46pt; " class="Default"><p>15059.0688</p></td></tr></table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php Yii::t('app','Close');?></button>
      </div>
    </div>
  </div>
</div>



<!-- Modal -->
<div class="modal fade" id="example-data" tabindex="-1" role="dialog" aria-labelledby="example-data">
  <div class="modal-dialog modal-full" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php Yii::t('app','Active Data');?></h4>
      </div>
      <div class="modal-body">
              
       
          <table class="table ">
              <thead>
              <tr>
                  <th> Date/Hour of Sample </th> 
                  <th> MgO</th>
                  <th>Al2O3 </th> 
                  <th>Fe2O3 </th>
                  <th>CaO </th>
                   <th>KH </th>
                  <th>N </th> 
                  <th>p </th>
              </thead>
          <tbody>
          <td>2018-06-23 00:00:00 </td> 
          <td>79.8 </td>
          <td>21.08</td>
          <td>4.84</td>
          <td>3.38</td>
          <td>68.28</td>
          <td>0.22</td>
          <td>0.66 </td>
          </tbody>
          </table>
        							
								

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo Yii::t('app','Close');?></button>
      </div>
    </div>
  </div>
</div>
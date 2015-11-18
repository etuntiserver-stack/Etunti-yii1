<?php

?>

<legend>
<h1><?php echo Yii::t('main','Avaimet'); ?></h1>
</legend>


<table class="table table-bordered table-stripped">
<thead>
 <tr>
  <th><?php echo Yii::t('main','Työntekijä'); ?></th>
  <th><?php echo Yii::t('main','Avain'); ?></th>
 </tr>
</thead>
<tbody>
  <?php
  foreach($model as $data)
  {
    $k = Kohteet::model()->findAll(" SUBSTRING_INDEX(kenella_on_avain, '//', 1) = '".$data->id."' ");
	$avaimet = '';
    foreach($k as $kohde)
    {
	$avaimet .= '<div class="row">
			<div class="col-sm-6">'.$kohde->osoite.'</div>
			<div class="col-sm-6"><b>'.$kohde->avain.'</b></div>
		     </div>';
    }

    echo '<tr>';
    echo '<td>'.$data->tekijan_nimi.'</td>';
    echo '<td>'.$avaimet.'</td>';
    echo '</tr>';
  }
  ?>
</tbody>
</table>

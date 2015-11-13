<?php

  $arrDate = array(1=>"Ma",2=>"Ti",3=>"Ke",4=>"To",5=>"Pe",6=>"La",7=>"Su");
  $columnDate = date("N/d.m",strtotime($data->aloitan));
  $explColDate = explode("/",$columnDate);

  $did = date("Ymd",strtotime($data->aloitan));
?>
<TR>

	<td>
	<?php 
		echo $arrDate[$explColDate[0]].', '.CHtml::encode(date("d.m.Y",strtotime($data->aloitan))); 
	?>
	</td>

	<!-- adminPaketti -->
	<?php
	 $tas = explode(",",Yii::app()->user->adminPaketti);
	 if(in_array('2',$tas)) : 
	?>
	<td><?php $this->renderPartial('//tyovuoroot/did',array('pvm'=>$data->aloitan,'tid'=>$data->tid,'from'=>'mobiili')); ?></td>
	<?php endif; ?>
	<!-- adminPaketti -->
	<td><?php $this->renderPartial('luetutpvmtid',array('pvm'=>$data->aloitan,'tid'=>$data->tid,'from'=>'mobiili')); ?></td>

	<td id="<?php echo $did.'_'.$data->tid; ?>">
	<?php $this->renderPartial('totpvmtid',array('pvm'=>$data->aloitan,'tid'=>$data->tid,'from'=>'mobiili')); ?>
	</td>
	<td id="yht_<?php echo $did.'_'.$data->tid; ?>"><?php $this->renderPartial('yhteensapvm',array('pvm'=>date("Y-m-d",strtotime($data->aloitan)),'tid'=>$data->tid,'from'=>'mobiili')); ?></td>


</TR>

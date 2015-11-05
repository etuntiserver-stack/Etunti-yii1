<?php

$did = date("Ymd",strtotime($data->aloitan));
?>
<TR>

	<td><?php echo CHtml::encode(date("d.m.Y",strtotime($data->aloitan))); ?></td>

	<!-- adminPaketti -->
	<?php
	 $tas = explode(",",Yii::app()->user->adminPaketti);
	 if(in_array('2',$tas)) : 
	?>
	<td><?php $this->renderPartial('//tyovuoroot/did',array('pvm'=>$data->aloitan,'tid'=>Yii::app()->session['tekija'],'from'=>'mobiili')); ?></td>
	<?php endif; ?>
	<!-- adminPaketti -->
	<td><?php $this->renderPartial('luetutpvmtid',array('pvm'=>$data->aloitan,'tid'=>Yii::app()->session['tekija'],'from'=>'mobiili')); ?></td>

	<td id="<?php echo $did.'_'.$data->tid; ?>">
	<?php $this->renderPartial('totpvmtid',array('pvm'=>$data->aloitan,'tid'=>Yii::app()->session['tekija'],'from'=>'mobiili')); ?>
	</td>
	<td id="yht_<?php echo $did.'_'.$data->tid; ?>"><?php $this->renderPartial('yhteensapvm',array('pvm'=>date("Y-m-d",strtotime($data->aloitan)),'tid'=>$data->tid,'from'=>'mobiili')); ?></td>


</TR>

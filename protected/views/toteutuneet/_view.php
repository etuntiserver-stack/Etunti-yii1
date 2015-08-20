<?php

?>
<tr>

	<td><?php echo CHtml::encode(date("d.m.Y",strtotime($data->aloitan))); ?></td>

	<!-- adminPaketti -->
	<?php if(Yii::app()->user->adminPaketti == '2') : ?>
	<td><?php $this->renderPartial('//tyovuoroot/did',array('pvm'=>$data->aloitan,'tid'=>$data->tid,'from'=>'mobiili')); ?></td>
	<?php endif; ?>
	<!-- adminPaketti -->
	<td><?php $this->renderPartial('luetutpvmtid',array('pvm'=>$data->aloitan,'tid'=>$data->tid,'from'=>'mobiili')); ?></td>
	<td><?php $this->renderPartial('totpvmtid',array('pvm'=>$data->aloitan,'tid'=>$data->tid,'from'=>'mobiili')); ?></td>

	<td>
	    <?php 
		$sun[$data->id] = $this->renderPartial('sunyhteensa',array('pvm'=>date("Y-m-d",strtotime($data->aloitan)),'tid'=>$data->tid),true);
		if($sun[$data->id] > 0)
		echo Yii::t('main', 'Sun. ').sprint($sun[$data->id]); 
	    ?>
	    <br>
	    <?php 
		$tot[$data->id] = $this->renderPartial('totyhteensa',array('pvm'=>date("Y-m-d",strtotime($data->aloitan)),'tid'=>$data->tid),true);
		if($tot[$data->id] > 0)
		echo Yii::t('main', 'Tot. ').sprint($tot[$data->id]); 
	    ?>
	    <br>
	    <?php 
		$ero[$data->id] = $sun[$data->id]-$tot[$data->id];
		if($ero[$data->id] > 0)
		echo Yii::t('main', 'Ero aika: ').sprint($ero[$data->id]); 
	    ?>
	</td>
</tr>

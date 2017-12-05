<?php

?>

<tr>
	<td>
		<?php echo date("d.m.Y  H:i",strtotime($data->time)); ?>
	</td>
	<td>
		<?php echo Yii::t('main', $data->tapahtuma); ?>
	</td>
	<td>
		<?php echo $this->pakettiMuutos($data->paketti); ?>
	</td>
</tr>

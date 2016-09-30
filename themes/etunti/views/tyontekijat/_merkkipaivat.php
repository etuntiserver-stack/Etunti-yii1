<?php

	$string = '';
	$varo = '';
	$expl = explode("-",$data->tekijan_henkilotunnus);
	if(isset($expl[0]) and isset($expl[1]))
	$string = date("Y")."-".floor(substr($expl[0], 2, 2))."-".floor(substr($expl[0], 0, 2));
	$string = date("Y-m-d", strtotime($string));

	$interval = 14;
	$now = date("Y-m-d");
	$nowPlusInt = date("Y-m-d", strtotime($string." -".$interval." day"));

	if($now >= $nowPlusInt and $now <= $string)
	$varo = "style='color:red'";
	else
	$varo = '';


?>


<tr>
	<td <?php echo $varo; ?>><?php echo CHtml::encode($data->tekijan_nimi); ?></td>
	<td><?php echo CHtml::encode(date("d.m.Y",strtotime($string))); ?></td>
	<td><?php echo CHtml::encode($data->tunnus); ?></td>
</tr>

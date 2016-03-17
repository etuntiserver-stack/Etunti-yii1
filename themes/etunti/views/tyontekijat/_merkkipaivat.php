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


	if($now >= $nowPlusInt and $string > $now)
	$varo = "style='border:2px red solid'";
	else
	$varo = '';

/*
	$datetime1 = new DateTime(date('Y-m-d'));
	$datetime2 = new DateTime(date('Y-m-d',strtotime($string)));
	$interval = $datetime1->diff($datetime2);

	if(substr($interval->format('%R%a days'), 0, 1) == "+")
	{
	$paljonko = $interval->format('%a');
	if($paljonko < 14)
	$varo = "style='border:2px red solid'";
	else
	$varo = "";
	} else {
	$varo = "";
	}
*/
?>


<tr <?php echo $varo; ?>>
	<td><?php echo CHtml::encode($data->tekijan_nimi); ?></td>
	<td><?php echo CHtml::encode(date("d.m.Y",strtotime($string))).' / '.$nowPlusInt; ?></td>
	<td><?php echo CHtml::encode($data->tunnus); ?></td>
</tr>

<?php

$string = '';
$varo = '';

if ($data->tekijan_henkilotunnus) {
	$datePart = substr($data->tekijan_henkilotunnus, 0, 6);
	$separator = substr($data->tekijan_henkilotunnus, 6, 1);
	// assume separator is "-"
	$yearPrefix = "19";
	// check for people born after 2000
	if ($separator === "A") {
		$yearPrefix = "20";
	}
	// people born before 1900 
	else if ($separator === "+") {
		$yearPrefix = "18";
	}
	$checkPart = substr($data->tekijan_henkilotunnus, 7);
	if ($datePart && $checkPart) {
		$year = substr($datePart, -2);
		$year = $yearPrefix . $year;
		$month = substr($datePart, 2, 2);
		$day = substr($datePart, 0, 2);
		$string = date("Y-m-d", strtotime($year . "-" . $month . "-" . $day));

		$interval = 14;
		$now = date("Y-m-d");
		$nowPlusInt = date("Y-m-d", strtotime($string . " -" . $interval . " day"));

		if ($now >= $nowPlusInt and $now <= $string)
			$varo = "style='color:red'";
		else
			$varo = '';
	}
}



?>


<tr>
	<td <?php echo $varo; ?>><?php echo CHtml::encode($this->etuSukunimi($data->id)); ?></td>
	<td><?php echo CHtml::encode(date("d.m.Y", strtotime($string))); ?></td>
	<td><?php echo CHtml::encode($data->tunnus); ?></td>
</tr>
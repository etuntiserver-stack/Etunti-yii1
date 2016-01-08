<?php
/* @var $this MobileController */
/* @var $data Mobile */

		$matka 	= '';
		$matkaM = '';
		$sum 	= '';
		$sumI 	= '';
		$korv 	= '';
		$lisatt	= '';
		$ennakko= '';


		$return = $this->toteutu($data->id,"palkkataulukko",$from,$to);

		$sum = $return[0];
		$sumI = $return[1]+$return[2]+$this->matkaIlta($data->id,$from,$to);

		if($return[0] != 0)
		$return[0] = $this->num($return[0]);

		if($return[1] != 0)
		$return[1] = '<b>Työt</b>:<br>'.$this->num($return[1]);
	        else
		$return[1] = '';

		if($return[2] != 0)
		$return[2] = $this->num($return[2]);

		if($return[3] != 0)
		$return[3] = $this->num($return[3]);

		$matkaIlta = 0;
		$matkaIlta = $this->matkaIlta($data->id,$from,$to);
		if($matkaIlta != 0)
		$matkaIlta = '<br><b>Matkat</b>:<br>'.$this->num($matkaIlta);
	        else
		$matkaIlta = '';




  $matkaM = $this->renderPartial('//mobile/tidfromtomatkat',array(
		'from'=>$from,
		'to'=>$to,
		'tid'=>$data->id
		),true);

		if($matkaM != 0)
		$matka = $this->sprint($matkaM).'<br>('.$this->num($matkaM).')';

  $lisatt = $this->renderPartial('//lisatyotunnit/tidfromto',array(
		'from'=>$from,
		'to'=>$to,
		'tid'=>$data->id
		),true);

  $korv = $this->renderPartial('//korvaukset/tidfromto',array(
		'from'=>$from,
		'to'=>$to,
		'tid'=>$data->id
		),true);

  $ennakko = $this->renderPartial('//ennakko/tidfromto',array(
		'from'=>$from,
		'to'=>$to,
		'tid'=>$data->id
		),true);


		$sum = $matkaM+$sum;
		$sum = $this->num($sum);
		$sumI = $this->num($sumI);
?>

<tr>

	<td class="tulostus_tekija"><?php echo CHtml::encode($data->tekijan_nimi); ?></td>
	<td><?php echo $tp; ?></td>
	<td><?php echo $matka; ?></td>
	<td><?php echo $return[0]; ?></td>
	<td><?php echo $sum; ?></td>
	<td><?php echo $return[1].$matkaIlta; ?></td>
	<td><?php echo $sumI; ?></td>
	<td><?php echo $return[2]; ?></td>
	<td><?php echo $return[3]; ?></td>
	<td><?php echo $this->num($sl); ?></td>
	<td><?php echo $spl; ?></td>
	<td><?php echo $this->num($ls); ?></td>
	<td><?php echo $korv; ?></td>
	<td><?php echo $lisatt; ?></td>
	<td><?php echo $ennakko; ?></td>
</tr>

	



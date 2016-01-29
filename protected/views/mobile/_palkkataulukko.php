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

	<td class="tulostus_tekija col1"><?php echo CHtml::encode($data->tekijan_nimi); ?></td>
	<td class="col2"><?php echo $tp; ?></td>
	<td class="col3"><?php echo $matka; ?></td>
	<td class="col4"><?php echo $return[0]; ?></td>
	<td class="col5"><?php echo $sum; ?></td>
	<td class="col6"><?php echo $return[1].$matkaIlta; ?></td>
	<td class="col7"><?php echo $sumI; ?></td>
	<td class="col8"><?php echo $return[2]; ?></td>
	<td class="col9"><?php echo $return[3]; ?></td>
	<td class="col10"><?php echo $this->num($pyhat); ?></td>
	<td class="col10"><?php echo $this->num($el); ?></td>
	<td class="col10"><?php echo $this->num($sl); ?></td>
	<td class="col11"><?php echo $spl; ?></td>
	<td class="col12"><?php echo $this->num($ls); ?></td>
	<td class="col13"><?php echo $korv; ?></td>
	<td class="col14"><?php echo $lisatt; ?></td>
	<td class="col15"><?php echo $ennakko; ?></td>
</tr>

	



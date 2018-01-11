<?php
/* @var $this KohteetController */
/* @var $data Kohteet */

/*
			$rakenne = json_decode($data->toinen_valikko_rakenne, true);
			$alasvetovaliko = '';

			if(isset($rakenne['values']))
			{

			   $nimike = array();
			   if(isset($rakenne['values']['nimike']))
			     foreach($rakenne['values']['nimike'] as $key=>$item)
				$nimike[] = $item;

			   $hinta_veroton = array();
			   if(isset($rakenne['values']['hinta_veroton']))
			     foreach($rakenne['values']['hinta_veroton'] as $key=>$item)
				$hinta_veroton[] = $item;

			   $hinta = array();
			   if(isset($rakenne['values']['nimike']))
			     foreach($rakenne['values']['hinta'] as $key=>$item)
				$hinta[] = $item;

			   $kesto = array();
			   if(isset($rakenne['values']['kesto']))
			     foreach($rakenne['values']['kesto'] as $key=>$item)
				$kesto[] = $item;


			   $i = 0;
			
			   $alasvetovaliko .= '<b>'.$rakenne['otsikko'].'</b>';
			   $alasvetovaliko .= '
			 	<table class="table">
				 <tr>
					<th>'.Yii::t('main', 'Nimike').'</th>
					<th>'.Yii::t('main', 'Hinta veroton').'</th>
					<th>'.Yii::t('main', 'Hinta').'</th>
					<th>'.Yii::t('main', 'Kesto').'</th>
				 </tr>
			   ';

			   foreach($nimike as $key=>$rivi)
			   {
			   $i++;

				if(isset($nimike[$key])) $nimike_value = $nimike[$key]; else $nimike_value = '';
				if(isset($hinta_veroton[$key])) $hinta_veroton_value = $hinta_veroton[$key]; else $hinta_veroton_value = '';
				if(isset($hinta[$key])) $hinta_value = $hinta[$key]; else $hinta_value = '';
				if(isset($kesto[$key])) $kesto_value = $kesto[$key]; else $kesto_value = '';

				$alasvetovaliko .= '
				<tr>
				  <td>'.$nimike_value.'</td>
				  <td>'.$hinta_veroton_value.'</td>
				  <td>'.$hinta_value.'</td>
				  <td>'.$kesto_value.'</td>
				</tr>
				';
			   }
			   $alasvetovaliko .= '
			 	</table>
			   ';
			}



			$rakenne = json_decode($data->lisapalvelut, true);
			$lisapalvelut = '';

			if(isset($rakenne['values']))
			{


			   $otsikko = array();
			   if(isset($rakenne['values']['otsikko']))
			     foreach($rakenne['values']['otsikko'] as $key=>$item)
				$otsikko[] = $item;

			   $kuvaus = array();
			   if(isset($rakenne['values']['kuvaus']))
			     foreach($rakenne['values']['kuvaus'] as $key=>$item)
				$kuvaus[] = $item;

			   $hinta_veroton = array();
			   if(isset($rakenne['values']['hinta_veroton']))
			     foreach($rakenne['values']['hinta_veroton'] as $key=>$item)
				$hinta_veroton[] = $item;

			   $hinta = array();
			   if(isset($rakenne['values']['hinta']))
			     foreach($rakenne['values']['hinta'] as $key=>$item)
				$hinta[] = $item;

			   $kesto = array();
			   if(isset($rakenne['values']['kesto']))
			     foreach($rakenne['values']['kesto'] as $key=>$item)
				$kesto[] = $item;


			   $i = 0;
			
			   $lisapalvelut .= '<br>
			 	<table class="table">
				 <tr>
					<th>'.Yii::t('main', 'Otsikko').'</th>
					<th>'.Yii::t('main', 'Kuvaus').'</th>
					<th>'.Yii::t('main', 'Hinta veroton').'</th>
					<th>'.Yii::t('main', 'Hinta').'</th>
					<th>'.Yii::t('main', 'Kesto').'</th>
				 </tr>
			   ';

			   foreach($otsikko as $key=>$rivi)
			   {
			   $i++;

				if(isset($otsikko[$key])) $otsikko_value = $otsikko[$key]; else $otsikko_value = '';
				if(isset($kuvaus[$key])) $kuvaus_value = $kuvaus[$key]; else $kuvaus_value = '';
				if(isset($hinta_veroton[$key])) $hinta_veroton_value = $hinta_veroton[$key]; else $hinta_veroton_value = '';
				if(isset($hinta[$key])) $hinta_value = $hinta[$key]; else $hinta_value = '';
				if(isset($kesto[$key])) $kesto_value = $kesto[$key]; else $kesto_value = '';


				$lisapalvelut .= '
				<tr>
				  <td>'.$otsikko_value.'</td>
				  <td>'.$kuvaus_value.'</td>
				  <td>'.$hinta_veroton_value.'</td>
				  <td>'.$hinta_value.'</td>
				  <td>'.$kesto_value.'</td>
				</tr>
				';
			   }
			   $lisapalvelut .= '
			 	</table>
			   ';
			}
*/
?>

<tr>
	<td>
		<?php echo CHtml::link('<i class="fa fa-pencil-square-o" aria-hidden="true" style="font-size: 110%"></i>', 
				array('update', 'id'=>$data->id), 
				array(
					'class'=>'btn btn-primary myBgColors', 
					'style'=>'color:white', 
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Muokkaa') 
				)
			); 
		?>
	</td>
	<td>
		<h3><?php echo $data->nimike; ?></h3>
	</td>
	<td>
		<?php echo $data->kategoria; ?>
	</td>
<?php /*
	<td>
		<?php echo $alasvetovaliko; ?>
	</td>
	<td>
		<?php echo $lisapalvelut; ?>
	</td>
*/ ?>
</tr>


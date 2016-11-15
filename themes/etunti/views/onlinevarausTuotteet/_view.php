<?php
/* @var $this KohteetController */
/* @var $data Kohteet */


			$rakenne = json_decode($data->toinen_valikko_rakenne, true);
			$alasvetovaliko = '';

			   $nimike = array();
			   foreach($rakenne['values']['nimike'] as $key=>$item)
				$nimike[] = $item;

			   $hinta = array();
			   foreach($rakenne['values']['hinta'] as $key=>$item)
				$hinta[] = $item;

			   $kesto = array();
			   foreach($rakenne['values']['kesto'] as $key=>$item)
				$kesto[] = $item;


			   $i = 0;
			
			   $alasvetovaliko .= '<b>'.$rakenne['otsikko'].'</b>';
			   $alasvetovaliko .= '
			 	<table class="table">
				 <tr>
					<th>'.Yii::t('main', 'Nimike').'</th>
					<th>'.Yii::t('main', 'Hinta').'</th>
					<th>'.Yii::t('main', 'Kesto').'</th>
				 </tr>
			   ';

			   foreach($nimike as $key=>$rivi)
			   {
			   $i++;
				$alasvetovaliko .= '
				<tr>
				  <td>'.$nimike[$key].'</td>
				  <td>'.$hinta[$key].'</td>
				  <td>'.$kesto[$key].'</td>
				</tr>
				';
			   }
			   $alasvetovaliko .= '
			 	</table>
			   ';



			$rakenne = json_decode($data->lisapalvelut, true);
			$lisapalvelut = '';

			   $otsikko = array();
			   foreach($rakenne['values']['otsikko'] as $key=>$item)
				$otsikko[] = $item;

			   $kuvaus = array();
			   foreach($rakenne['values']['kuvaus'] as $key=>$item)
				$kuvaus[] = $item;

			   $hinta = array();
			   foreach($rakenne['values']['hinta'] as $key=>$item)
				$hinta[] = $item;

			   $kesto = array();
			   foreach($rakenne['values']['kesto'] as $key=>$item)
				$kesto[] = $item;


			   $i = 0;
			
			   $lisapalvelut .= '<br>
			 	<table class="table">
				 <tr>
					<th>'.Yii::t('main', 'Otsikko').'</th>
					<th>'.Yii::t('main', 'Kuvaus').'</th>
					<th>'.Yii::t('main', 'Hinta').'</th>
					<th>'.Yii::t('main', 'Kesto').'</th>
				 </tr>
			   ';

			   foreach($otsikko as $key=>$rivi)
			   {
			   $i++;
				$lisapalvelut .= '
				<tr>
				  <td>'.$otsikko[$key].'</td>
				  <td>'.$kuvaus[$key].'</td>
				  <td>'.$hinta[$key].'</td>
				  <td>'.$kesto[$key].'</td>
				</tr>
				';
			   }
			   $lisapalvelut .= '
			 	</table>
			   ';
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
		<?php echo $data->nimike; ?>
	</td>
	<td>
		<?php echo $data->selitysteksti; ?>
	</td>
	<td>
		<?php echo $alasvetovaliko; ?>
	</td>
	<td>
		<?php echo $lisapalvelut; ?>
	</td>
</tr>


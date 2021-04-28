<?php

?>
<tr>
	<td>
		<?php echo $data->Fullname.' #'.$data->id; ?>
	</td>
	<td width="70%">
		<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapse_id_<?=$data->id?>" aria-expanded="false" aria-controls="collapseExample">
	    	<?=Yii::t('main', 'Näytä rivit')?> <i class="caret"></i>
		</button>
		<div class="collapse" id="collapse_id_<?=$data->id?>">
		<?php
		$yht_summ = 0;
		if(isset($kk_hinta[$data->id]) or isset($lista[$data->id]))
		{
			echo '<table class="well table table-striped">';
			echo '<tr>';
			echo '<th>'.Yii::t('main', 'Tuote').'</th>';
			echo '<th>'.Yii::t('main', 'Määrä').'</th>';
			echo '<th>'.Yii::t('main', 'Alv').'</th>';
			echo '<th>'.Yii::t('main', 'Hinta').'</th>';
			echo '<th>'.Yii::t('main', 'Yhteensä').'</th>';
			echo '<th>'.Yii::t('main', 'Freetext').'</th>';
			echo '</tr>';
		}
		
		// <-- KK hinta
		$kk_arr 	= [];
		$yht_kk 	= 0;
		if(isset($kk_hinta[$data->id]))
		{
			echo '<tr>';
			echo '<td colspace="3"><h3>Kuukausi laskenta</h3></th>';
			echo '</tr>';
			
			foreach($kk_hinta[$data->id] as $kohde_id => $arr)
			{
				$kk_arr[] 	= [
					'tuote' 	=> $arr['tuote'], 
					'maara' 	=> 1, 
					'hinta' 	=> $arr['hinta'], 
					'alv' 		=> $arr['alv'],
					'free_text'	=> $arr['free_text']
				];
				
				$yht_kk 			+= $arr['hinta'];
				$yht_summ			+= $arr['hinta'];
				echo '<tr>';
				echo '<td>'.$arr['tuote'].'</td>';
				echo '<td>1</td>';
				echo '<td>'.$arr['alv'].'</td>';
				echo '<td>'.$arr['hinta'].'</td>';
				echo '<td>'.$arr['hinta'].'</td>';
				echo '<td>'.$arr['free_text'].'</td>';
				echo '</tr>';
			}
		}
		
		$mob_tv_arr	= [];
		$yht_tunnit = 0;
		if(isset($lista[$data->id]))
		{
			echo '<tr>';
			echo '<td colspace="3"><h3>Hyväksytyt tunnit Mobiilista</h3></th>';
			echo '</tr>';

			foreach($lista[$data->id] as $aika => $arr)
			{			
				foreach($arr as $k => $v)
				{
					$tv_link		= '<span class="pull-right text-danger">ei tuotteita</span>';
					$yht_tunnit 	+= $v['maara'];
					$tuote			= $v['attributes']['kohde_kannasta'];
					$hinta			= 0;
					$alv			= 0;
					$tuoteID		= 0;
					$maara 			= $this->num($v['maara']);
					$free_text		= date("d.m.Y", $aika);
					
					if(isset($v['tyovuoro_tuotteet']['paa_tuote']))
					{
						$this_pvm 	= $v['tyovuoro_tuotteet']['paa_tuote']['tv_pvm'];
						$tv_link 	= CHtml::link('<span class="text-success">näytä työvuoro</span>',
							[
							  sprintf('/tyovuoroot/beta?mode=vko&year=%s&week=%s&tv_id=%s', date("Y", strtotime($this_pvm)), date("W", strtotime($this_pvm)), $v['attributes']['tv_id'])
							],
							[
							  'class' => 'pull-right',
							  'target' => '_blank'
							]
						);
					
						$tuote		= $v['tyovuoro_tuotteet']['paa_tuote']['nimike'];
						$free_text 	= date("d.m.Y", $aika).': '.$v['attributes']['kohde_kannasta'];
						$tuoteID	= $v['tyovuoro_tuotteet']['paa_tuote']['tuoteID'];
						$hinta		= $v['tyovuoro_tuotteet']['paa_tuote']['hinta'];
						$alv		= $v['tyovuoro_tuotteet']['paa_tuote']['alv'];
					}
						
					$yht_summ		+= $maara*$hinta;
					$mob_tv_arr[] 	= [
						'tuote' 		=> $tuote,
						'tuoteID' 		=> $tuoteID,
						'maara' 		=> $maara, 
						'hinta' 		=> $hinta, 
						'alv' 			=> $alv, 
						'tv_id' 		=> $v['attributes']['tv_id'],
						'pikkuviesti' 	=> $v['pikkuviesti'],
						'free_text'		=> $free_text
					];
					
					echo '<tr>';
					echo '<td>'.$tuote.$tv_link.'</td>';
					echo '<td>'.$maara.'</td>';
					echo '<td>'.$alv.'</td>';
					echo '<td>'.$hinta.'</td>';
					echo '<td>'.($maara*$hinta).'</td>';
					echo '<td>'.$free_text.'</td>';
					echo '</tr>';
					
					if(isset($v['tyovuoro_tuotteet']['lisa_tuotteet']))
					{
						foreach($v['tyovuoro_tuotteet']['lisa_tuotteet'] as $tuote)
						{

							$hinta			= $tuote['hinta'];
							$alv			= $tuote['alv'];
							$yht_summ		+= $tuote['maara']*$hinta;
							$mob_tv_arr[] 	= [
								'tuote' 	=> $tuote['nimike'],
								'tuoteID' 	=> $tuote['tuoteID'],
								'maara' 	=> $tuote['maara'], 
								'hinta' 	=> $hinta, 
								'alv' 		=> $alv, 
								'tv_id' 	=> $v['attributes']['tv_id'],
								'free_text'	=> $free_text
							];
							
							echo '<tr>';
							echo '<td>'.$tuote['nimike'].$tv_link.'</td>';
							echo '<td>'.$tuote['maara'].'</td>';
							echo '<td>'.$alv.'</td>';
							echo '<td>'.$hinta.'</td>';
							echo '<td>'.($tuote['maara']*$tuote['hinta']).'</td>';
							echo '<td>'.$free_text.'</td>';
							echo '</tr>';
						}
					}
				}
			}
		}

		echo '<tr>';
		echo '<th></th>';
		echo '<th></th>';
		echo '<th></th>';
		echo '<th></th>';
		echo '<th>'.number_format($yht_summ, 2, ',', ' ').' &euro;</th>';
		echo '</tr>';
		echo '</table>';
		?>
		</div>
	</td>
	<td class="text-center"><?=$yht_kk?></td>
	<td class="text-center"><?=$this->sprint($yht_tunnit)?></td>
	<td width="20%">
		<?php
			echo '<form action="create?l_asiakkaat=true&asiakasnumero='.$data->asiakasnumero.'" method="POST" target="_blank">';
			echo '<textarea style="display:none" name="la_asiakkaat_mobiili">'.json_encode($mob_tv_arr).'</textarea>';
			echo '<textarea style="display:none" name="la_asiakkaat_kk">'.json_encode($kk_arr).'</textarea>';
			echo '<select class="form-control" name="with_mobile">';
			echo '<option value="1">Mobiili + Kuukausi</option>';
			echo '<option value="2">Vain mobiili</option>';
			echo '<option value="3">Vain kuukausi</option>';
			echo '</select>';
			echo '<input type="submit" class="btn btn-warning btn-block" value="Luo lasku">';
			echo '</form>';
		?>
	</td>
</tr>

<?php
	$asiakas = '';
	if($data->tyyppi == 'henkilo')
		$asiakas = $data->yhteyshenkilo;
	if($data->tyyppi == 'yritys')
		$asiakas = $data->yrityksen_nimi;
?>
<tr>
	<td>
		<?php echo $asiakas.' #'.$data->id; ?>
	</td>
	<td class="p15">
		<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapse_id_<?=$data->id?>" aria-expanded="false" aria-controls="collapseExample">
	    	<?=Yii::t('main', 'Näytä rivit')?> <i class="caret"></i>
		</button>
		<div class="collapse" id="collapse_id_<?=$data->id?>">
		<?php
		$li = [];
		$yht_tunnit = 0;
		if(isset($lista[$data->id]))
		{
			echo '<table class="table table-striped">';
			echo '<tr>';
			echo '<th>'.Yii::t('main', 'Työntekijä').'</th>';
			echo '<th>'.Yii::t('main', 'Pvm.').'</th>';
			echo '<th>'.Yii::t('main', 'Osoite').'</th>';
			echo '<th>'.Yii::t('main', 'Tunnit').'</th>';
			echo '</tr>';
			foreach($lista[$data->id] as $aika => $items)
			{
				foreach($items as $k => $v)
				{
					$yht_tunnit += $v['maara'];
					$li[] = [
						'tunnit_from' => $v['tunnit_from'],
						'id' => $v['id'],
						'freetext' => date("d.m.Y", $aika).' '.$v['osoite'], 
						'maara' => $this->num($v['maara']),
						'kohde' => $v['kohde']
					];
					echo '<tr>';
					echo '<td>'.$v['tekijan_nimi'].'</td>';
					echo '<td>'.date("d.m.Y", $aika).'</td>';
					echo '<td>'.$v['osoite'].'</td>';
					echo '<td>'.$this->num($v['maara']).'</td>';
					echo '</tr>';
				}
			}
			echo '</table>';
		}
		?>
		</div>
	</td>
	<td class="text-center"><?=$this->sprint($yht_tunnit)?></td>
	<td class="text-right">
		<?php
		if(count($li) > 0){

			echo '<form action="create?l_asiakkaat=true&asiakasnumero='.$data->asiakasnumero.'" method="POST" target="_blank">';
			echo $tuotteet_lista;
			echo '<textarea style="display:none" name="tr_rivit">'.json_encode($li).'</textarea>';
			echo '<input type="submit" class="btn btn-warning btn-block" value="Luo lasku">';
			echo '</form>';
		}
		?>
	</td>
</tr>

<?php
$asiakas = 'Asiakas: '.$data->id;
$asiakas = (!empty($data->etunimi)? $data->etunimi.' '.$data->sukunimi: '').($data->tyyppi == 'yritys')? (!empty(trim($data->etunimi))? ' - ': '') .$data->yrityksen_nimi : '';
?>
<tr>
<td>
<?php echo CHtml::link($asiakas, 
		array('update', 'id'=>$data->id)
); ?>
</td>
<td>
<?php
	if(isset($data->kohteet))
	{
		foreach($data->kohteet as $item)
		{
			echo $item->osoite.' - TAG numero: <b>'.(!empty($item->tag_id)? $item->tag_id: '<span class="text-danger">Ei esitelty</span>').'</b><br>';
		}
	}
?>
</td>
</tr>

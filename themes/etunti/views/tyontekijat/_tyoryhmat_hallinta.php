<?php
/* @var $this TyontekijatController */
/* @var $data Tyontekijat */

$mod = '';
$tek = '';
?>

<tr>
	<td>
		<?php echo CHtml::button(Yii::t('main', 'Tallenna'), 
				array(
					'class'=>'tallenna btn btn-primary myBgColors', 
					'style'=>'color:white', 
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Tallenna'),
					'id'=>$data->id 
				)
			); 
		?>

	   <?php     
		echo CHtml::link('X', '#', array(
					'submit'=>array('tyoryhmat_hallinta', "id"=>$data->id, "poista"=>"true"), 
					'confirm' => 'Haluatko varmaasti poistaa?',
					'style'=>'color:white', 
					'class'=>'btn btn-danger'
				)
			);
	   ?>

	</td>
	<td class="col-sm-3">
		<input type="text" class="form-control m2" value="<?php echo $data->value; ?>" id="a2_<?php echo $data->id; ?>" for="<?php echo $data->id; ?>">
	</td>

	<td class="col-sm-3">
		<?php
			// <-- tyontekijat
			if(isset($tyontekijat) and is_array($tyontekijat))
			{
				$tek .= '<select class="form-control form-group m4" id="a4_'.$data->id.'" multiple for="'.$data->id.'">';
				foreach($tyontekijat as $tekija)
				{
					if( is_array(json_decode($tekija->tyoryhma)) and in_array($data->value, json_decode($tekija->tyoryhma)) )
						$tek .= '<option value="'.$tekija->id.'" selected>'.$tekija->tekijan_nimi.'</option>';
					elseif( !is_array(json_decode($tekija->tyoryhma)) and $data->value == $tekija->tyoryhma)
						$tek .= '<option value="'.$tekija->id.'" selected>'.$tekija->tekijan_nimi.'</option>';
					else
						$tek .= '<option value="'.$tekija->id.'">'.$tekija->tekijan_nimi.'</option>';
				}
				$tek .= '</select>';
				echo $tek;
			}
			// tyontekijat -->
		?>
	</td>

	<td class="col-sm-3">
		<?php
			// <-- Työryhmä
			if(isset($admins) and is_array($admins))
			{
				$mod .= '<select class="form-control form-group m3" id="a3_'.$data->id.'" multiple for="'.$data->id.'">';
				foreach($admins as $adm)
				{
					$value2 = json_decode($data->value2);
					if( is_array($value2) and in_array($adm->id, $value2) )
						$mod .= '<option value="'.$adm->id.'" selected>'.$adm->adm_nimi.'</option>';
					else
						$mod .= '<option value="'.$adm->id.'">'.$adm->adm_nimi.'</option>';
				}
				$mod .= '</select>';
				echo $mod;
			}
			// Työryhmä -->
		?>
	</td>

</tr>

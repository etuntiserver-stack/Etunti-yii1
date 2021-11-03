<?php
/* @var $this AdministratorsController */
/* @var $data Administrators */
/* @var $roleTitles array of role titles, [someRoleId => someRoleTitle, ...] */

$cl = '';
$tt = '';
if(!empty($data->token))
{
	$cl = 'text-warning';
	$tt = 'data-toggle="tooltip" data-placement="top" title="'.Yii::t('main', 'Käyttäjätunnusta ei vahvistettu').'"';
}
?>



<tr class="<?php echo $cl; ?>" <?php echo $tt; ?>>
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
		<?php echo $data->adm_login; ?>
	</td>
	<td>
		<?php echo $data->adm_email; ?>
	</td>
	<td>
		<?php echo $data->adm_nimi; ?>
	</td>
	<td>
		<?= $roleTitles[$data->status] ?>
	</td>
</tr>


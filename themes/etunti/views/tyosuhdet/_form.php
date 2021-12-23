<?php
/* @var $this TyosuhdetController */
/* @var $model Tyosuhdet */
/* @var $form CActiveForm */




if($model->tyopvm_kk != 0)
{
$d1 = date("Y-m-d");
$d2 = date("Y-m-d", strtotime($model->alku));
$result = (int)abs((strtotime($d1) - strtotime($d2))/(60*60*24*30));

if($result < 12)
  $l = 2;
else
  $l = 2.5;



	$year = date("Y");
	$a_year = date("Y");
	$l_year = date("Y");
	if( date("Ymd") > date("Ymd",strtotime("31.03.".$year)) ){
		$a_year = (int)date("Y");
		$l_year = (int)date("Y")+1;

	} else	if( date("Ymd") < date("Ymd",strtotime("31.03.".$year)) ){
		$a_year = (int)date("Y")-1;
		$l_year = (int)date("Y");
	}

	$alku = date("d.m.Y", strtotime("01.04.".$a_year));
	$loppu = date("d.m.Y", strtotime("31.03.".$l_year));
	$lomakausi = $alku.' - '.$loppu;


$d3 = $alku;
$d4 = date("Y-m-d");
$result2 = (int)abs((strtotime($d3) - strtotime($d4))/(60*60*24*30));

	$c = $result2;


  //echo 'L: '.$l.'<br>';
  $kkmaara = $c;
  $pv = $c*$l;
  $green=0;

  echo '<br><br>';

  $m = Yii::app()->createController('Mobile');
  $tulos = "true";	

  echo '<div class="row">
	 <div class="col-sm-6">';
  echo '<h3>'.Yii::t('main', 'Vuosiloma laskenta').' <b>'.$lomakausi.'</b></h3>';
  echo '<table class="table table-bordered table-hover">
	<tr>
	<th>'.Yii::t('main', 'Kuukausi').'</th>';

	if($model->tyopvm_kk >= 14)
	echo '<th>'.Yii::t('main', 'Työpäiviä').'</th>';

	if($model->tyopvm_kk < 14)
	echo '<th>'.Yii::t('main', 'Tunnit').'</th>';

	echo '</tr>';
  while($c) {
	$from = date("Y-m-01", strtotime("-".$c--." month"));
	$to = date("Y-m-d", strtotime($from." last day +1 month"));
	$tp = $m[0]->TP($model->tid,$from,$to);
	$toteutu = $m[0]->toteutu($model->tid,'palkkataulukko',$from,$to);

	echo '<tr>';
	echo '<td width=1>'.date("m.Y",strtotime($from)).'</td>';

	// TP
	if($model->tyopvm_kk >= 14)
	{
	echo '<td>';
	   if($tp < 14)
	   {
		echo '<span class="btn btn-danger btn-block btn-sm">'.$tp.'</span>';
		$tulos = "false";
  	   } else {
		echo '<span class="btn btn-success btn-block btn-sm">'.$tp.'</span>';
		$green++;
	   }
	echo '</td>';
	}

	// Toteutu
	if($model->tyopvm_kk < 14)
	{
	echo '<td>';
	   if((int)$m[0]->num($toteutu[0]) < 35)
	   {
		echo '<span class="btn btn-danger btn-block btn-sm">'.(float)$m[0]->num($toteutu[0]).'</span>';
		$tulos = "false";
  	   } else {
		echo '<span class="btn btn-success btn-block btn-sm">'.(float)$m[0]->num($toteutu[0]).'</span>';
		$green++;
	   }
	echo '</td>';
	}
	
	echo '</tr>';

	if($tp <= 14 or (int)$m[0]->num($toteutu[0]) < 35)
	{
		$tulos = "false";
		//break;
	}
  }


  if($tulos == 'true')
  $t = '<h3>'.Yii::t('main', 'Vuosiloma päiviä').': '.$pv.'</h3>';
  else
  $t = '<h3>'.Yii::t('main', 'Vuosiloma päiviä').': '.($l*$green).'</h3>';

	echo '<tr>
		<th></th>
		<th>'.$t.'</th>
	</tr>';


  echo '</table>';



  echo ' </div>
	</div>


	<hr>';

}
if(!isset($model->id)){
	$model->alku = date("d.m.Y");
}
if(!empty($model->alku)){
	$model->alku = date("d.m.Y",strtotime($model->alku));
}
if(!empty($model->loppu)){
	$model->loppu = date("d.m.Y",strtotime($model->loppu));
}

if(isset($model->id))
echo '<input type="hidden" id="modelID" value="'.$model->id.'">';
if(isset($_GET['id']))
$model->tid = $_GET['id'];
?>



<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tyosuhdet-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

		<?php echo $form->hiddenField($model,'tid'); ?>
<?php if($laaja == 1) : ?>
<div class="row">
  <div class="col-sm-4">
  <legend>
    <h2><?php echo Yii::t('main', 'TYÖSUHTEET'); ?></h2>
  </legend>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'alku'); ?>
		<?php echo $form->textField($model,'alku',array('size'=>20,'maxlength'=>20,'class'=>'form-control datepickerFI')); ?>
		<?php echo $form->error($model,'alku'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'loppu'); ?>
		<?php echo $form->textField($model,'loppu',array('size'=>20,'maxlength'=>20,'class'=>'form-control datepickerFI')); ?>
		<?php echo $form->error($model,'loppu'); ?>
	</div>

	<?php
		// if $model->loppu is defined, we'll not hide the next element (termination_reason)
		// but if it's empty, we can default to just hiding it. a JS script will show the
		// element if the user changes $model->loppu
		$shouldHide = "hide";
		if($model->loppu) {
			$shouldHide = "";
		}
	?>
	<div id="termination_reason_section" class="section fill mb5 ashidd_a <?=$shouldHide?>">
		<?php echo $form->labelEx($model, "termination_reason") ; ?>
		<div class="input-group">
			<?php
				$list = Valikkoot::model()->findAll("select_type='termination_reason'");
			?>
			<?php echo $form->dropDownList($model, "termination_reason", CHtml::listData($list, "id", "value"),
				["empty" => "Valitse", "class" => "form-control"]); ?>
			<?php echo $form->error($model, "tyoryhma"); ?>
			<span class="input-group-btn">
				<span class="btn btn-primary myBgColors muokaValiko" for="termination_reason">
					<i class="fa fa-pencil-square-o"></i>
				</span>
			</span>
		</div>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyopvm_kk'); ?>
		<?php echo $form->numberField($model,'tyopvm_kk',array('maxlength'=>2,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tyopvm_kk'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'vktyoaika'); ?>
		<?php echo $form->textField($model,'vktyoaika',array('size'=>10,'maxlength'=>10,'class'=>'form-control timepicker_false')); ?>
		<?php echo $form->error($model,'vktyoaika'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'nimike'); ?>
		<?php echo $form->textField($model,'nimike',array('size'=>40,'maxlength'=>40,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'nimike'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'palkkausmuoto'); ?>
		<?php echo $form->textField($model,'palkkausmuoto',array('size'=>30,'maxlength'=>30,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'palkkausmuoto'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'palkka_tyyppi'); ?>

	   <div class="input-group">
		<?php
		$list = array();
      		$l = Valikkoot::model()->findAll(" select_type='palkka_tyyppi' ",array('order' => "select_type"));
		if(count($l) == 0)
      		{
			$new_val = new Valikkoot;
			$new_val->select_type = "palkka_tyyppi";
			$new_val->value = "Tuntipalkkalaiset";
			if($new_val->save())
	      			$l = Valikkoot::model()->findAll(" select_type='palkka_tyyppi' ",array('order' => "select_type"));
			else
				var_dump($new_val->getErrors());
		}

			$arr = json_decode($model->palkka_tyyppi);
			echo '<select name="Tyosuhdet[palkka_tyyppi]" class="form-control" title="Valitse">';
			echo '<option value=>Valitse</option>';
			foreach($l as $data)
			{
				if($data->value == $model->palkka_tyyppi)
			    		echo '<option value="'.$data->value.'" selected>'.$data->value.'</option>';

				else
			    		echo '<option value="'.$data->value.'">'.$data->value.'</option>';
			}
			echo '</select>';
		
        	?>
		<span class="input-group-btn">
			<span class="btn btn-primary myBgColors muokaValiko" for="palkka_tyyppi"><i class="fa fa-pencil-square-o"></i></span>
		</span>
	   </div>
	</div>

	<div class="section fill mb5">
		<?php $model->tuntihinta = str_replace(",",".",$model->tuntihinta); ?>
		<?php echo $form->labelEx($model,'tuntihinta'); ?>
		<?php echo $form->numberField($model,'tuntihinta',array('size'=>10,'maxlength'=>10,'class'=>'form-control', "step"=>"any")); ?>
		<?php echo $form->error($model,'tuntihinta'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'matka_thinta'); ?>
		<?php echo $form->textField($model,'matka_thinta',array('size'=>10,'maxlength'=>10,'class'=>'form-control', "step"=>"any")); ?>
		<?php echo $form->error($model,'matka_thinta'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'lippu_kuumaks'); ?>
		<?php echo $form->textField($model,'lippu_kuumaks',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'lippu_kuumaks'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'koe_loppu'); ?>
		<?php echo $form->numberField($model,'koe_loppu',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'koe_loppu'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'koe_hinta'); ?>
		<?php echo $form->textField($model,'koe_hinta',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'koe_hinta'); ?>
	</div>

  </div><div class="col-sm-4">
  <legend>
    <h2><?php echo Yii::t('main', 'Tiedot palkan maksua varten.'); ?></h2>
  </legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'veronumero'); ?>
		<?php echo $form->textField($model,'veronumero',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'veronumero'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tuloraja_ajalle'); ?>
		<?php echo $form->textField($model,'tuloraja_ajalle',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tuloraja_ajalle'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'perusprosentti'); ?>
		<?php echo $form->textField($model,'perusprosentti',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'perusprosentti'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'lisaprosentti'); ?>
		<?php echo $form->textField($model,'lisaprosentti',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'lisaprosentti'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kuukaudessa'); ?>
		<?php echo $form->textField($model,'kuukaudessa',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'kuukaudessa'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kahdessa_viikossa'); ?>
		<?php echo $form->textField($model,'kahdessa_viikossa',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'kahdessa_viikossa'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'viikossa'); ?>
		<?php echo $form->textField($model,'viikossa',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'viikossa'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'paivassa'); ?>
		<?php echo $form->textField($model,'paivassa',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'paivassa'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'atk_varten'); ?>
		<?php echo $form->textField($model,'atk_varten',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'atk_varten'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'yksi_tuloraja'); ?>
		<?php echo $form->textField($model,'yksi_tuloraja',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'yksi_tuloraja'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyoelakevakuutuksen_tyyppi'); ?>
		<?php
		$list = array(1 =>'Tyel', 2 => 'MYEL', 3 => 'YEL', 4 => 'Ei eläkevakuutettu' );
        	echo $form->dropDownList($model, 'tyoelakevakuutuksen_tyyppi', $list,
		array('class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'tyoelakevakuutuksen_tyyppi'); ?>
	</div>
	
	<legend><?php echo Yii::t('main', 'Työttömyysvakuutus'); ?></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyottomyysvakuutus_tyyppi'); ?>
		<?php
		$list = array(
			'automatichandling' =>'Automaattinen käsittely',
			'under17yearsold' =>'Alle 17-vuotias',
			'17to64yearsold' =>'17-64 vuotias',
			'over65yearsold' =>'yli 65-vuotias',
			'partowner' =>'osaomistaja',
		);
        	echo $form->dropDownList($model, 'tyottomyysvakuutus_tyyppi', $list,
		array('class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'tyottomyysvakuutus_tyyppi'); ?>
	</div>

  </div><div class="col-sm-4">
  <legend>
    <h2><?php echo Yii::t('main', 'Työsuhteet historia'); ?></h2>
  </legend>
	
	<div class="section fill mb5">
		<?php 
		$th = TyosuhdeHistoria::model()->findAll(" tid='".$model->tid."' ");
		foreach($th as $item){
			$arr = json_decode($item->arr);
			echo '<span data-toggle="collapse" data-target="#demo_'.$item->id.'" class="btn btn-block btn-primary myBgColors">Työsuhde #'.$item->id.' <i class="caret"></i></span>
			<div id="demo_'.$item->id.'" class="collapse"><br><p>';
			foreach($arr as $k => $ar){
				echo $form->labelEx($model, $k).' '.$ar.'<br>';
			}
			echo '</p></div>';
		echo '<hr>';
		}
		?>
	</div>
  </div>

</div><!-- form -->
<?php endif; ?>
	<br>
	<div class="section buttons">
		<?php echo CHtml::submitButton('Tallenna',array('class'=>'btn btn-primary myBgColors tallennaKaksiLomaketta')); ?>
	</div>

<?php $this->endWidget(); ?>




<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.mask.js"></script>


<script type="text/javascript">
$(document).ready(function(){

  $('#Tyosuhdet_vktyoaika').mask('00:00',{
        placeholder: "__:__"
  });

  $('#tyosuhdet-form').on('submit',function(e) {

     if( $('#modelID').val() )
     {
	  $.ajax({
		  url: location.protocol + "//" + location.host + '/index.php/tyosuhdet/update?id='+$('#modelID').val(),
		  data:$(this).serialize(),
		  type:'POST',
		  success:function(data){
			// expect an object containing response and contract-changed fields, i.e.
			// {"response": "saveOK", "contract-changed": 1}
			data = JSON.parse(data);
			//alert(data);
			
			let input = $("<input>").attr("type", "hidden").attr("name", "contract-changed").val(data["contract-changed"] ?? 0);
			$('#tyontekijat-form').append(input);
			$('#tyontekijat-form').submit();
	   	},
		error:function(data){
		console.log(data);
	    	}
	  });

     } else {
	  $.ajax({
		  url: location.protocol + "//" + location.host + '/index.php/tyosuhdet/create',
		  data:$(this).serialize(),
		  type:'POST',
		  success:function(data){
			data = JSON.parse(data);
			//alert(data);
			$('#tyontekijat-form').submit();
	   	},
		error:function(data){
		console.log(data);
	    	}
	  });
     }

	  e.preventDefault();
  });

  $("#Tyosuhdet_loppu").blur((e) => {
	if(e.target.value) {
		$("#termination_reason_section").removeClass("hide");
		$('[for="Tyosuhdet_termination_reason"]').css({"color":"red"});
	}
	// we could hide the reason element in an else statement,
	// but if the user didn't remove the reason before removing the date
	// they can no longer access it.
  });

});
</script>



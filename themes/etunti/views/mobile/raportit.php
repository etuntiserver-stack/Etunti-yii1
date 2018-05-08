<?php
     $tas = array();
   if(isset(Yii::app()->user->adminPaketti)) 
     $tas = explode(",",Yii::app()->user->adminPaketti);

/*
	// <-- Järjestelmanvalvojan kuluvia ryhmiä
	$criteria = new CDbCriteria();
	$criteria->order = " value ";
	$criteria->condition = " 
		select_type='tyoryhma'	
		AND value2 LIKE '%\"".Yii::app()->user->adminID."\"%'
	";
	$valikot = Valikkoot::model()->findAll($criteria);
	
	$tyoryhmat = array();
	foreach($valikot as $data){
		$tyoryhmat[] = $data->value;
	}

	$ryhmaBody = '';
	if(count($tyoryhmat) > 0){
		$ryhmaBody .= '
		<div class="alert alert-default">
			<h3>'.Yii::app()->user->nimi.'</h3> '.Yii::t('main', 'Työntekijöiden työryhmät').': <b>'.implode(", ", $tyoryhmat).'</b>
		</div>';
	}
	// Järjestelmanvalvojan kuluvia ryhmiä -->
*/
?>


        <!-- begin: .tray-center -->
        <div class="tray-center">

        <h2 class="myBgColors p10"> 
	<div class="form-inline">
	 <div class="form-group">
		<i class="glyphicon glyphicon-th-list"></i> <?php echo Yii::t('main', 'Raportit'); ?>
	 </div><div class="form-group col-sm-offset-1">
		<?php
		( Yii::app()->request->getParam('aktiivinen') ) ? $selectedAktiivinen = Yii::app()->request->getParam('aktiivinen') : $selectedAktiivinen = 'kaikki';

		$a = Valikkoot::model()->findAll(" select_type='aktiivinen' ");
		   $tal = array();
		   $tal['kaikki'] = 'Kaikki';
		foreach($a as $v){
		$exV = explode("/",$v->value);
		   if(isset($exV[0]) and isset($exV[1]))
		   $tal[$exV[1]] = $exV[0];
		}
		//ksort($tal);
		echo CHtml::dropDownList('aktiivinen','aktiivinen', $tal, array('class'=>'form-control', 'options' => array( $selectedAktiivinen => array('selected'=>true))));
		?>
	 </div>
	</div>
	</h2>


<script>
$(document).ready(function(){

  $('#aktiivinen').change(function(){
	var thisVal = $(this).val();
	window.location.href="raportit?aktiivinen=" + thisVal;
  });


});
</script>

<?php /*
          <div class="row">
           <div class="col-sm-12">
		<?php echo $ryhmaBody; ?>
           </div>
	  </div>	
	  <br>
*/ ?>

<!--
          <div class="row">
           <div class="col-sm-12">
		<table class="table table-striped table-bordered">
		 <tr>
		  <th>blaa</th>
		  <th>blaa 2</th>
		 </tr>
		 <tr>
		  <th>45</th>
		  <th>78</th>
		 </tr>
		</table>
           </div>
	  </div>
	  <br>
-->

          <div class="row">


	  <!-- Luetut -->
            <div class="admin-form col-sm-4">
              <div class="panel heading-border">
		<h3 class="p10"><?php echo Yii::t('main', 'Luetut'); ?></h3>
                <div class="panel-body bg-light">
                 <div class="row">


    <form action="#" id="luetutForm" target="_blank" method=POST>
    <input type="hidden" name="method" value="luetut">

      <div class="row">
       <div class="col-sm-6">

                          <label class="field prepend-icon">

   			    <input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?php if(isset(Yii::app()->session['from'])) echo date('d.m.Y', strtotime(Yii::app()->session['from'])); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>

       </div><div class="col-sm-6">

                          <label class="field prepend-icon">

   			    <input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?php if(isset(Yii::app()->session['to'])) echo date('d.m.Y', strtotime(Yii::app()->session['to'])); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>

       </div>
      </div>

      <br>

      <div class="row">
       <div class="col-sm-6">
      	<?php
	   $criteria = new CDbCriteria();

		// <-- Return order etu ja sukunimella
		$site = Yii::app()->createController('Site');
		$criteria = $site[0]->etuSukunimiCriteria($criteria);
		//     Return order etu ja sukunimella -->

	   if( Yii::app()->request->getParam('aktiivinen') and Yii::app()->request->getParam('aktiivinen') != 'kaikki'){
	       $criteria->condition = " aktiivinen='".$selectedAktiivinen."' ";
	   }

		// <-- Tyoryhmat
		$checkOikeus = "tyoryhmat_4_".Yii::app()->user->adminStatus;
		$site = Yii::app()->createController('Site');
		if( $site[0]->checkOikeusFields($checkOikeus) == 0 ){
			$tt = Yii::app()->createController('Tyontekijat');
			$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper();
			$ids = implode(",", $tt_arr);
			if( count($tt_arr) > 0 ){
	        		$criteria->addCondition (" id IN ($ids)");
			} else {
		        	$criteria->condition = ' 1!=1 ';
			}
		}
		//    Tyoryhmat -->

	   $tlist = Tyontekijat::model()->findAll($criteria);

	   echo '<select name="tekija[]" multiple class="mult">';
	   foreach($tlist as $val){
/*
		if(count($tyoryhmat) > 0 and in_array($val->tyoryhma, $tyoryhmat))
			echo '<option value="'.$val->id.'" selected>'.$this->etuSukunimi($val->id).'</option>';
		else
*/
			echo '<option value="'.$val->id.'">'.$this->etuSukunimi($val->id).'</option>';
	   }
	   echo '</select>';
	?>
       </div>
       <div class="col-sm-6">
   	<?php
	   $criteria = new CDbCriteria();
	   $criteria->order = " osoite ";

		// <-- Tyoryhmat
		$checkOikeus = "tyoryhmat_4_".Yii::app()->user->adminStatus;
		$site = Yii::app()->createController('Site');
		if( $site[0]->checkOikeusFields($checkOikeus) == 0 ){
			$arr = $site[0]->TyoryhmatHelper();
			$ids = implode(",", $arr);
			if( count($arr) > 0 ){
				$criteria->addCondition (" tyoryhma IN ($ids) ");
			} else {
				$criteria->condition = " 1!=1 ";
			}
		}
		//    Tyoryhmat -->

    	   $k = CHtml::listData(Kohteet::model()->findAll($criteria), 'id', 'osoite');
    	   echo '<select name="kohteet" class="form-control" id="kohteet" title="Kohteet">';
	       echo '<option value="kaikki">'.Yii::t('main', 'Kaikki kohteet').'</option>';
    	   foreach($k as $key=>$val){
       	 	echo '<option value="'.$key.'">'.$val.'</option>';
    	   }
    	   echo '</select>';
   	?>
       </div>
      </div>
      <br>

      <div class="row">
       <div class="col-sm-6">
    	   <select name="ilman[]" class="eilasketa"  multiple="multiple"  title="<?php echo Yii::t('main', 'Ei lasketa'); ?>">
    	   <option value="Lounastauko"><?php echo Yii::t('main', 'Lounastauko'); ?></option>
    	   <option value="MATKA"><?php echo Yii::t('main', 'Matka'); ?></option>
    	   </select>
       </div>

       <div class="col-sm-6">
       <?php
   	$kohdenTnimike = Valikkoot::model()->findAll(" select_type='siivous' ",array('order' => "select_type"));
    	echo '<select class="form-control" name="siivousPaaSivulla">';
        echo '<option value="">'.Yii::t('main', 'Kohteen työnimike').'</option>';
    	foreach($kohdenTnimike as $key=>$val){
    		echo '<option value="'.$val->value.'">'.$val->value.'</option>';
    	}
   	echo '</select>';
   	?>
       </div>

      </div>
      <br>
    </form>

      <div class="row">
       <div class="col-sm-12">
        <div class="pull-right">
    	  <button class="btn btn-primary myBgColors submitPrintSivuLuetut"><i class="fa fa-print" aria-hidden="true"></i></button>
    	  <button class="btn btn-primary myBgColors submitExcelluetut"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
    	  <button class="btn btn-primary myBgColors submitPDFluetut"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
        </div>
       </div>
      </div>

                 </div>
                </div>
              </div>
            </div>


<script>
$(document).ready(function(){

  $('.submitPrintSivuLuetut').click(function(){

	var input = $("<input id='luoPrintSivu'>")
               .attr({"type":"text","name":"luoPrintSivu"}).val("true");
	$('#luetutForm').append(input).submit();
	$('#luoPrintSivu').remove();

  });

  $('.submitPDFluetut').click(function(){

	var input = $("<input id='luoPDF'>")
               .attr({"type":"text","name":"luoPDF"}).val("true");
	$('#luetutForm').append(input).submit();
	$('#luoPDF').remove();

  });

  $('.submitExcelluetut').click(function(){

	var input = $("<input id='luoExcel'>")
               .attr({"type":"text","name":"luoExcel"}).val("true");
	$('#luetutForm').append(input).submit();
	$('#luoExcel').remove();

  });

});
</script>




	  <!-- Toteutuneet -->
            <div class="admin-form col-sm-4">
              <div class="panel heading-border">
		<h3 class="p10"><?php echo Yii::t('main', 'Toteutuneet'); ?></h3>
                <div class="panel-body bg-light">
                 <div class="row">


    <form action="#" id="toteutuneetForm" target="_blank" method=POST>
    <input type="hidden" name="method" value="toteutuneet">

      <div class="row">
       <div class="col-sm-6">
                          <label class="field prepend-icon">

   			    <input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?php if(isset(Yii::app()->session['from'])) echo date('d.m.Y', strtotime(Yii::app()->session['from'])); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>

       </div><div class="col-sm-6">

                          <label class="field prepend-icon">

   			    <input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?php if(isset(Yii::app()->session['to'])) echo date('d.m.Y', strtotime(Yii::app()->session['to'])); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
       </div>
      </div>

      <br>

      <div class="row">
       <div class="col-sm-6">
      	<?php
	   echo '<select name="tekija[]" multiple class="mult">';
	   foreach($tlist as $val){
/*
		if(count($tyoryhmat) > 0 and in_array($val->tyoryhma, $tyoryhmat))
			echo '<option value="'.$val->id.'" selected>'.$this->etuSukunimi($val->id).'</option>';
		else
*/
			echo '<option value="'.$val->id.'">'.$this->etuSukunimi($val->id).'</option>';
	   }
	   echo '</select>';
	?>
       </div>
       <div class="col-sm-6">
   	<?php
    	   echo '<select name="kohteet" class="form-control" id="kohteet" title="Kohteet">';
	       echo '<option value="kaikki">'.Yii::t('main', 'Kaikki kohteet').'</option>';
    	   foreach($k as $key=>$val){
       	 	echo '<option value="'.$key.'">'.$val.'</option>';
    	   }
    	   echo '</select>';
   	?>
       </div>
      </div>
      <br>

      <div class="row">
       <div class="col-sm-6">
    	   <select name="ilman[]" class="eilasketa"  multiple="multiple"  title="<?php echo Yii::t('main', 'Ei lasketa'); ?>">
    	   <option value="Lounastauko"><?php echo Yii::t('main', 'Lounastauko'); ?></option>
    	   <option value="MATKA"><?php echo Yii::t('main', 'Matka'); ?></option>
    	   </select>
       </div>

       <div class="col-sm-6">
       <?php
    	echo '<select class="form-control" name="siivousPaaSivulla">';
        echo '<option value="">'.Yii::t('main', 'Kohteen työnimike').'</option>';
    	foreach($kohdenTnimike as $key=>$val){
    		echo '<option value="'.$val->value.'">'.$val->value.'</option>';
    	}
   	echo '</select>';

   	?>
       </div>

      </div>
      <br>
    </form>


      <div class="row">
       <div class="col-sm-12">
        <div class="pull-right">
    	  <button class="btn btn-primary myBgColors submitPrintSivuToteutuneet"><i class="fa fa-print" aria-hidden="true"></i></button>
    	  <button class="btn btn-primary myBgColors submitExceltoteutuneet"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
    	  <button class="btn btn-primary myBgColors submitPDFtoteutuneet"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
        </div>
       </div>
      </div>


                 </div>
                </div>
              </div>
            </div>



<script>
$(document).ready(function(){

  $('.submitPrintSivuToteutuneet').click(function(){

	var input = $("<input id='luoPrintSivu'>")
               .attr({"type":"text","name":"luoPrintSivu"}).val("true");
	$('#toteutuneetForm').append(input).submit();
	$('#luoPrintSivu').remove();

  });

  $('.submitPDFtoteutuneet').click(function(){

	var input = $("<input id='luoPDF'>")
               .attr({"type":"text","name":"luoPDF"}).val("true");
	$('#toteutuneetForm').append(input).submit();
	$('#luoPDF').remove();

  });

  $('.submitExceltoteutuneet').click(function(){

	var input = $("<input id='luoExcel'>")
               .attr({"type":"text","name":"luoExcel"}).val("true");
	$('#toteutuneetForm').append(input).submit();
	$('#luoExcel').remove();

  });

});
</script>


<?php if(in_array('2',$tas)) : ?>

	  <!-- Toteutuneet ja suunnittelut ero -->
            <div class="admin-form col-sm-4">
              <div class="panel heading-border">
		<h3 class="p10"><?php echo Yii::t('main', 'Toteutuneen ja suunnitellun työn erot'); ?></h3>

                <div class="panel-body bg-light">
                 <div class="row">


    <form action="#" id="totJasunEroForm" target="_blank" method=POST>
    <input type="hidden" name="method" value="LuetutToteutuneetEro">

      <div class="row">
       <div class="col-sm-6">
                          <label class="field prepend-icon">

   			    <input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?php if(isset(Yii::app()->session['from'])) echo date('d.m.Y', strtotime(Yii::app()->session['from'])); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>


       </div><div class="col-sm-6">

                          <label class="field prepend-icon">

   			    <input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?php if(isset(Yii::app()->session['to'])) echo date('d.m.Y', strtotime(Yii::app()->session['to'])); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
       </div>
      </div>

      <br>

      <div class="row">
       <div class="col-sm-6">
      	<?php
	   echo '<select name="tekija[]" multiple class="mult">';
	   foreach($tlist as $val){
/*
		if(count($tyoryhmat) > 0 and in_array($val->tyoryhma, $tyoryhmat))
			echo '<option value="'.$val->id.'" selected>'.$this->etuSukunimi($val->id).'</option>';
		else
*/
			echo '<option value="'.$val->id.'">'.$this->etuSukunimi($val->id).'</option>';
	   }
	   echo '</select>';
	?>
       <br>
       </div>
       <div class="col-sm-6">
   	<?php
    	   echo '<select name="kohteet" class="form-control" id="kohteet" title="Kohteet">';
	       echo '<option value="kaikki">'.Yii::t('main', 'Kaikki kohteet').'</option>';
    	   foreach($k as $key=>$val){
       	 	echo '<option value="'.$key.'">'.$val.'</option>';
    	   }
    	   echo '</select>';
   	?>
       <br>
       </div>
       <div class="col-sm-6">
	<select name="is_kaikki" class="form-control">
	 <option value="erot"><?php echo Yii::t('main', 'Näytä erot'); ?></option>
	 <option value="kaikki"><?php echo Yii::t('main', 'Näytä kaikki'); ?></option>
	</select>
       </div>
       <div class="col-sm-6">
   	<?php
    	echo '<select class="form-control" name="siivousPaaSivulla">';
        echo '<option value="">'.Yii::t('main', 'Kohteen työnimike').'</option>';
    	foreach($kohdenTnimike as $key=>$val){
    		echo '<option value="'.$val->value.'">'.$val->value.'</option>';
    	}
   	echo '</select>';
   	?>
       </div>
      </div>
      <br>
    </form>

      <div class="row">
       <div class="col-sm-12">
        <div class="pull-right">
    	  <button class="btn btn-primary myBgColors submitPrintSivutotEro"><i class="fa fa-print" aria-hidden="true"></i></button>
    	  <button class="btn btn-primary myBgColors submitExceltotEro"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
    	  <button class="btn btn-primary myBgColors submitPDFtotEro"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
        </div>
       </div>
      </div>


                 </div>
                </div>
              </div>
            </div>



<script>
$(document).ready(function(){

  $('.submitPrintSivutotEro').click(function(){

	var input = $("<input id='luoPrintSivu'>")
               .attr({"type":"text","name":"luoPrintSivu"}).val("true");
	$('#totJasunEroForm').append(input).submit();
	$('#luoPrintSivu').remove();

  });

  $('.submitPDFtotEro').click(function(){

	var input = $("<input id='luoPDF'>")
               .attr({"type":"text","name":"luoPDF"}).val("true");
	$('#totJasunEroForm').append(input).submit();
	$('#luoPDF').remove();

  });

  $('.submitExceltotEro').click(function(){

	var input = $("<input id='luoExcel'>")
               .attr({"type":"text","name":"luoExcel"}).val("true");
	$('#totJasunEroForm').append(input).submit();
	$('#luoExcel').remove();

  });

});
</script>




	  <!-- Lomat ja poissaolot -->
            <div class="admin-form col-sm-4">
              <div class="panel heading-border">
		<h3 class="p10"><?php echo Yii::t('main', 'Lomat ja poissaolot'); ?></h3>

                <div class="panel-body bg-light">
                 <div class="row">


    <form action="#" id="lomatForm" target="_blank" method=POST>
    <input type="hidden" name="method" value="lomatJaPoissaolot">

      <div class="row">
       <div class="col-sm-6">
                          <label class="field prepend-icon">

   			    <input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?php if(isset(Yii::app()->session['from'])) echo date('d.m.Y', strtotime(Yii::app()->session['from'])); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>


       </div><div class="col-sm-6">

                          <label class="field prepend-icon">

   			    <input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?php if(isset(Yii::app()->session['to'])) echo date('d.m.Y', strtotime(Yii::app()->session['to'])); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
       </div>
      </div>

      <br>

      <div class="row">
       <div class="col-sm-6">
      	<?php
	   echo '<select name="tekija" class="form-control">';
	   echo '<option value="kaikki">'.Yii::t('main', 'Kaikki työntekijät').'</option>';
	   foreach($tlist as $val){
			echo '<option value="'.$val->id.'">'.$this->etuSukunimi($val->id).'</option>';
	   }
	   echo '</select>';
	?>
       <br>
       </div>
       <div class="col-sm-6">
       <?php
   	$lomat = Valikkoot::model()->findAll(" select_type='vuosilomat' ",array('order' => "select_type"));
    	echo '<select class="form-control selectpicker" multiple name="status[]" title="Lomat">';
        echo '<option value=""></option>';
    	foreach($lomat as $key=>$val){
		$expl = explode("/",$val->value);
		if(isset($expl[0]) and isset($expl[1]))
    		echo '<option value="'.$expl[0].'">'.$expl[1].'</option>';
    	}
   	echo '</select>';
   	?>
       </div>
      </div>
      <br>

    </form>

      <div class="row">
       <div class="col-sm-12">
        <div class="pull-right">
    	  <button class="btn btn-primary myBgColors submitPrintSivulomat"><i class="fa fa-print" aria-hidden="true"></i></button>
    	  <button class="btn btn-primary myBgColors submitExcellomat"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
    	  <button class="btn btn-primary myBgColors submitPDFlomat"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
        </div>
       </div>
      </div>


                 </div>
                </div>
              </div>
            </div>



<script>
$(document).ready(function(){

  $('.submitPrintSivulomat').click(function(){

	var input = $("<input id='luoPrintSivu'>")
               .attr({"type":"text","name":"luoPrintSivu"}).val("true");
	$('#lomatForm').append(input).submit();
	$('#luoPrintSivu').remove();

  });

  $('.submitPDFlomat').click(function(){

	var input = $("<input id='luoPDF'>")
               .attr({"type":"text","name":"luoPDF"}).val("true");
	$('#lomatForm').append(input).submit();
	$('#luoPDF').remove();

  });

  $('.submitExcellomat').click(function(){

	var input = $("<input id='luoExcel'>")
               .attr({"type":"text","name":"luoExcel"}).val("true");
	$('#lomatForm').append(input).submit();
	$('#luoExcel').remove();

  });

});
</script>

<?php endif; ?>



	  </div><!--row-->



        <!-- loppu: .tray-center -->
        </div>






<script>
$(document).ready(function(){

$('.mult').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Työntekijät"); ?>',
	selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
	allSelectedText: '<?php echo Yii::t("main", "Kaikki"); ?>',
	nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
	numberDisplayed: 0,
	buttonWidth: '100%',
});

$('.eilasketa').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Ei lasketa"); ?>',
	selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
	allSelectedText: '<?php echo Yii::t("main", "Kaikki"); ?>',
	nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
	numberDisplayed: 0,
	buttonWidth: '100%',
});



});
</script>






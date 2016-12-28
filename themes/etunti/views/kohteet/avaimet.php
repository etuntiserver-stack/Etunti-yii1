<?php

?>

<style>
.table tbody>tr>td{
    	vertical-align: top;
}
</style>









<?php if(!isset($_POST['tulosta'])) : ?>
        <!-- begin: .tray-center -->
        <div class="tray-center">


   <!-- tulostus -->
   <div class="pull-right">
     <button class="btn btn-primary btn-sm myBgColors avaimetHyvaksyntaTaulu"><?php echo Yii::t('main', 'Tulosta'); ?></button>
     <?php /*
     <form action="#" target="_blank" method="POST">
      <input type="submit" name="tulosta" class="btn btn-success btn-sm myBgColors" value="PDF">
     </form>
     */ ?>
   </div>
   <!-- tulostus -->



              <h2 class="myBgColors p10"> <i class="fa fa-key"></i> <?php echo Yii::t('main', 'Avaimet'); ?> 
		</h2>



   	    <form id="yhtveto" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="yhtvetoform">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">


                      <div class="col-md-2">
                        <div class="section">
                          <label class="field">


				<?php
				$selected = array();
				if(isset($_POST['Tekija'])) $selected = $_POST['Tekija'];
		   		$site = Yii::app()->createController('Site');
		   		$tyontekiatLista = $site[0]->tyontekiatLista( 
					'Tekija', // name
					null, // class
					'tyontekijat', // id
					$selected, //selected
					1 // aktiivinen
				);
				echo $tyontekiatLista;
				?>


                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input" name="avain" value="<?php if(isset($_POST['avain'])) echo $_POST['avain']; ?>" placeholder="<?php echo Yii::t('main', 'Avain'); ?>">

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-key"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2 col-md-offset-5">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Hae'); ?>">
		      </div>

                    </div>



                </div>
              </div>
            </div>

	    </form>


        <!-- loppu: .tray-center -->
        </div>


<br>

<?php endif; ?>

<div class="admin-form">
  <div class="panel heading-border">
   <div class="panel-body">

	<div class="row">
 	 <div class="table-responsive">
	  <table class="table table-stripped" id="avaimetHyvaksyntaTaulu">
	   <thead class="myBgColors">
		 <tr>
		  <th><?php echo Yii::t('main','Työntekijä'); ?></th>
		  <th><?php echo Yii::t('main','Avain'); ?></th>
		 </tr>
	   </thead>
	   <tbody>
  <?php
  foreach($model as $data)
  {
    $k = Kohteet::model()->findAll(" SUBSTRING_INDEX(kenella_on_avain, '//', 1) = '".$data->id."' ");
	$avaimet = '';
    $i = 0;
    foreach($k as $kohde)
    {
    $i++;
	$avaimet .= '<div class="row">
			<div class="col-sm-6"> 
				<b>'.$kohde->etu_suku_nimet.'</b><br>
				'.$kohde->osoite.', '.$kohde->pnumero.', '.$kohde->kaupunki.'
			</div>
			<div class="col-sm-6"><b>'.$kohde->avain.'</b></div>
		     </div>';
    }

    echo '<tr>';
    echo '<td>'.$this->etuSukunimi($data->id).'</td>';
    echo '<td>'.$avaimet.'</td>';
    echo '</tr>';
  }
  ?>
	    </tbody>
	   </table>
 	 </div>
	</div>

   </div>
  </div>
</div>




<?php if(!isset($_POST['tulosta'])) : ?>
<script type="text/javascript">
$(document).ready(function(){


$(".avaimetHyvaksyntaTaulu").click(function(){
    var divToPrint = document.getElementById('avaimetHyvaksyntaTaulu');
    var htmlToPrint = '' +
        '<style type="text/css">' +
	'.table tbody>tr>td{' +
	    	'vertical-align: top;' +
	'}' +
        'table {' +
	'border-collapse: collapse;' +
	'border: 0;' +
        '}' +
        'table th, table td {' +
        'border:1px solid #333;' +
        'padding:3px 5px;' +
        '}' +
        '</style>';
    htmlToPrint += divToPrint.outerHTML;
    newWin = window.open("");
    newWin.document.write(htmlToPrint);
    newWin.print();
    newWin.close();
});




$(".haemob").click(function(){
	$("#yhtveto").submit();
});


$('#deselAll').click(function(){
   $('#tyontekijat').selectpicker('deselectAll');
});


$('#selAll').click(function(){
   $('#tyontekijat').selectpicker('selectAll');
});


$('#tyontekijat').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Tyhjä"); ?>',
	selectAllText: 'Valitse kaikki',
	allSelectedText: 'Kaikki',
	nSelectedText: 'valittu',
	numberDisplayed: 0,
	buttonWidth: '100%',
        maxHeight: 300,
});


});
</script>
<?php endif; ?>

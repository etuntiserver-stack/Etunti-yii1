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
                          <label class="field">


				<?php
				$selected = array();
				if(isset($_POST['Tekija'])) $selected = $_POST['Tekija'];
		   		$site = Yii::app()->createController('Site');
		   		$tyontekiatLista = $site[0]->tyontekiatLista( 
					'Tekija', // name
					null, // class
					'tyontekijat_passiviset', // id
					$selected, //selected
					'2' // aktiivinen
				);
				echo $tyontekiatLista;
				?>


                          </label>
                        </div>
                      </div>

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
					'tyontekijat_lopettaneet', // id
					$selected, //selected
					'3' // aktiivinen
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

                      <div class="col-md-2 col-md-offset-1">
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
		  <th></th>
		 </tr>
	   </thead>
	   <tbody>
  <?php
  foreach($model as $data)
  {

    $criteria = new CDbCriteria();
    $criteria->condition = " 
	SUBSTRING_INDEX(kenella_on_avain, '//', 1) = '".$data->id."'
    ";

    $k = Kohteet::model()->findAll($criteria);
	$avaimet = '';
    $i = 0;
    $avaimet = '<table class="table table-bordered table-striped">
    <tr>
	<th>'.Yii::t('main','Kohde').'</th>
	<th>'.Yii::t('main','Avain').'</th>
    </tr>
    ';
    foreach($k as $kohde)
    {
    $i++;
	$avaimet .= '
		      <tr><td> 
				<b>'.$kohde->etu_suku_nimet.'</b><br>
				'.$kohde->osoite.', '.$kohde->pnumero.', '.$kohde->kaupunki.'
			</td>
			<td>
				<b>'.$kohde->avain.'</b>
			</td>
		      </tr>
		     ';
    }
    $avaimet .= '</table>';


    echo '<tr>';
    echo '<td><h3>'.$this->etuSukunimi($data->id).'</h3></td>';
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
	nonSelectedText: '<?php echo Yii::t("main", "Aktiiviset työntekijät"); ?>',
	selectAllText: 'Valitse kaikki',
	allSelectedText: 'Kaikki',
	nSelectedText: 'valittu',
	numberDisplayed: 0,
	buttonWidth: '100%',
        maxHeight: 300,
});

$('#tyontekijat_passiviset').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Passiviset työntekijät"); ?>',
	selectAllText: 'Valitse kaikki',
	allSelectedText: 'Kaikki',
	nSelectedText: 'valittu',
	numberDisplayed: 0,
	buttonWidth: '100%',
        maxHeight: 300,
});

$('#tyontekijat_lopettaneet').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Lopettaneet työntekijät"); ?>',
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

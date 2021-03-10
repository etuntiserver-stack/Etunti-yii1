<?php $asetukset=Asetukset::model()->findbypk(1); ?>

        <!-- begin: .tray-center -->
        <div class="tray-center">


	<?php if($info != ''): ?>
	<div class="alert <?php echo (isset($is_error) && $is_error) ? 'alert-danger' : 'alert-success' ?>"><?php echo $info; ?></div>
	<?php endif; ?>


        <div class="pull-right myBgColors p10">
  <?php
  // Procountor send all approved on this page -button. This needs a confirm
  // dialog as it is a potentially harmful action. See the related jQuery code.
  if ($asetukset->palvelu_tyyppi == 5) {
    echo "<button class='lahetaSivuProcountor btn btn-success myBgColors'
      style='color:white' data-toggle='tooltip' data-placement='top' title=''
      data-original-title='Lähetä tällä sivulla näkyvät hyväksytyt laskut asiakkaille.'
      '>" . Yii::t('main', 'Lähetä kaikki hyväksytyt tällä sivulla') . "</button>";
  }
  ?>
	<?php echo CHtml::link(Yii::t('main', 'Lähettämättömät'), 
		array('index', 'lahettamattomat'=>'true'), 
		array(
			'class'=>'btn btn-primary myBgColors', 
			'style'=>'color:white', 
			'data-toggle'=>'tooltip', 
			'data-placement'=>'top', 
			'title'=>Yii::t('main', 'Hyväksytyt lähettämättömät laskut') 
		)
	); 
	?>
        </div>

        <h2 class="myBgColors p10"> <i class="fa fa-barcode"></i> <?php echo Yii::t('main', 'LASKU'); ?> 
		<?php echo CHtml::link('',Yii::app()->request->baseUrl.'/index.php/lasku/create',array('class'=>'btn btn-default fa fa-plus')); ?>
	</h2>



   	    <form id="mobForm" action="#" class="form-inline" method="GET">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body">

                    <!-- Input Icons -->
                    <div class="row">


                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">


    		<?php 
       		$criteria = new CDbCriteria();
		$criteria->order = " etunimi ";


        	$a = Asiakkaat::model()->findAll($criteria);

		if(isset($_GET['asiakasLaskulle']))
			$asiakasLaskulle = $_GET['asiakasLaskulle']; 
		else 
			$asiakasLaskulle = '';

		echo '<input type="hidden" id="asiakasSelected" value="'.$asiakasLaskulle.'">';
		echo '<select name="asiakasLaskulle" id="asiakaatLista" class="gui-input">';
		echo '<option value=>'.Yii::t('main', 'Valitse asiakas').'</option>';
		foreach($a as $aa)
		{
		    echo '<option value="'.$aa->asiakasnumero.'">'.$aa->Fullname.'</option>';
		}
		echo '</select>';
		?>


                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>


                        <div class="section">
                         <label class="field select">

				<?php
				if(isset($_GET['tilaLaskulle']))
				echo '<input type="hidden" id="getTila" value="'.$_GET['tilaLaskulle'].'">';
				?>

				<select name="tilaLaskulle" id="tilaLaskulle" class="gui-input">
				<option value=><?php echo Yii::t('main', 'Valitse tilanne'); ?></option>
				<option value="0"><?php echo Yii::t('main', 'Luotu'); ?></option>
				<option value="1"><?php echo Yii::t('main', 'Hyväksytty (Lähettämätömät)'); ?></option>
				<option value="2"><?php echo Yii::t('main', 'Lähetetty'); ?></option>
				<option value="3"><?php echo Yii::t('main', 'Maksettu'); ?></option>
				</select>

                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>


                      </div>


                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input datepickerFI" name="from" value="<?php echo date('d.m.Y', strtotime($from)); ?>" >

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>

                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input" name="laskuosoite" value="<?php if(isset($_GET['laskuosoite'])) echo $_GET['laskuosoite']; ?>" placeholder="<?php echo Yii::t('main', 'Osoite'); ?>">

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-bookmark"></i>
                            </label>
                          </label>
                        </div>

                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input datepickerFI" name="to" value="<?php echo date('d.m.Y', strtotime($to)); ?>" >

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input" name="laskunumero" value="<?php if(isset($_GET['laskunumero'])) echo $_GET['laskunumero']; ?>" placeholder="Laskunumero..">

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-bookmark"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="viitenumero"  class="gui-input" value="<?php if(isset($_GET['viitenumero'])) echo $_GET['viitenumero']; ?>" placeholder="Viitenumero..">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-bookmark"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Hae'); ?>">
		      </div>
                    </div>

                </div>
              </div>
            </div>

	    </form>


        <!-- loppu: .tray-center -->
        </div>


	<?php if($lahettamattomat == true): ?>
	<h3 class="alert alert-primary myBgColors"><?php echo Yii::t('main', 'Lähettämättömät laskut'); ?>
    <button class="valitseKaikki btn btn-sm btn-default btn-group"><?php echo Yii::t('main', 'Valitse kaikki'); ?></button>
		<span id="lahetaValitsemmat"></span>
	</h3>
	<?php endif; ?>


  <div class="panel heading-border">
   <div class="panel-body">

<div class="table-responsive">
  <table class="table table-striped" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <?php if($lahettamattomat == true): ?>
  <th></th>
  <?php endif; ?>
  <th></th>
  <th></th>
  <th><?php echo Yii::t('main', 'Nro.'); ?></th>
  <th><?php echo Yii::t('main', 'Asiakas'); ?></th>
  <th><?php echo Yii::t('main', 'Osoite'); ?></th>
  <th><?php echo Yii::t('main', 'Viitenumero'); ?></th>
  <th><?php echo Yii::t('main', 'Luotu'); ?></th>
  <th><?php echo Yii::t('main', 'Tilanne'); ?></th>
  <th><?php echo Yii::t('main', 'Tapahtuma pvm'); ?></th>
  <th><?php echo Yii::t('main', 'Yhteensä'); ?></th>
  <th><?php echo Yii::t('main', 'Avoinna'); ?></th>
  <th><?php echo Yii::t('main', 'Laskun tyyppi'); ?></th>
  </tr>
  </thead>
  <?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
	'viewData' => array( 'lahettamattomat'=>$lahettamattomat ), // YOUR OWN VARIABLES
  	'template'=>'{items}<table class="table table-striped table-condensed"></table><br/>{pager}',


	'pager' => array(
           'firstPageLabel'=>'<<',
           'prevPageLabel'=>'< Edellinen',
           'nextPageLabel'=>'Seuraava >',
           'lastPageLabel'=>'>>',
           //'maxButtonCount'=>'10',
           'header'=>'<h3>Siirry sivulle:</h3>',
           'cssFile'=>false,
       ), 

  )); ?>
  </table>
</div>

   </div>
  </div>



	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>


<script type="text/javascript">
$(document).ready(function(){

$('#asiakaatLista').val($('#asiakasSelected').val());

$(".valitseKaikki").click(function(){
	var $chk=$('#mobileTable input:checkbox');
	$chk.prop('checked',$chk.is(':checked') ? null:'checked');
	if($chk.is(':checked'))
	{
		$('#lahetaValitsemmat').html('<button class="btn btn-sm btn-default btn-group lahetaNamat">Lähetä</button>');
	} else {
		$('#lahetaValitsemmat').html('');
	}
});

$(".valitseLahetettavaksi").click(function(){
	var onkoChecked = false;
	$('#mobileTable input:checkbox').each(function () {
           if (this.checked) {
		onkoChecked = true;
	   }
	});
	if(onkoChecked)
	{
		$('#lahetaValitsemmat').html('<button class="btn btn-sm btn-default btn-group lahetaNamat">Lähetä</button>');
	} else {
		$('#lahetaValitsemmat').html('');
	}
});

// Procountor: Send all approved invoices on this page.
$('.lahetaSivuProcountor').click(function() {

  // Confirm this action as it is a potentially harmful one.
  if (!confirm('Haluatko varmasti lähettää kaikki tämän sivun hyväksytyt laskut?'))
    return;

  // Build list of IDs. Just send all IDs on this page to the action. The action
  // will check whether an invoice should be sent.
  var ids = [];
  $('i.link[for]').each(function() {
    ids.push($(this).attr('for'));
  });

  // Build hidden form for POST.
  var form = '';
  $.each(ids, function( key, value ) { form += '<input type="hidden" name="ids[]" value="'+value+'">'; });

  // Submit and continue to action.
  $('<form action="laheta_procountor" method="POST">' + form + '</form>').appendTo($(document.body)).submit();
});

// Send all, or only selected invoices.
$(document).delegate(".lahetaNamat", "click", function() {
  $('#mobileTable input:checkbox').each(function() {
    if (this.checked) {

      // Get invoice ID.
      var id = $(this).attr('for');

      // Save this element to access the current row later in AJAX callback.
      var temp = $(this);

      $.ajax({
        url: 'laheta_valitsemmat?id=' + id,
        /*type: "POST",
        data: { id : id },*/
        success: function(data) {

          // Check if there was an error. If so, stop the loop now.
          if (data.trim() != 'OK') {
            alert(data);
            return false; // break invoice loop
          } else {

            // Action successful, find the status column of this row and change the text.
            temp.parent().parent().parent().find('td').each(function(index) {
              if ($(this).text().trim() == 'Lasku hyväksytty') {
                $(this).text('Lasku lähetetty');
                return false; // break
              }
            });
          }
        }
      });
    }
  });
});



if($('#getTila').val())
{
   $("#tilaLaskulle option[value="+$('#getTila').val()+"]").prop('selected', true);
}

$(".haemob").click(function(){
	$("#mobForm").submit();
});


$(".fa-history").click(function(){

	var thisid = $(this).attr("for");


        $.ajax({

           url: 'get_historia',
	   type: 'POST',
	   data: { id : thisid },
           success: function(data){
		//console.log(data);
		$("#showres").modal().html(JSON.parse(data));
           }
        });

});

});
</script>

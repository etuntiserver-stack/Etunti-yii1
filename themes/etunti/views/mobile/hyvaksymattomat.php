<?php
	//$asetukset=Asetukset::model()->findbypk(1);

?>

        <!-- begin: .tray-center -->
        <div class="tray-center">

        <h2 class="myBgColors p10"> <i class="fa fa-barcode"></i> <?php echo Yii::t('main', 'LASKU'); ?> 
		<?php echo CHtml::link('',Yii::app()->request->baseUrl.'/index.php/lasku/create',array('class'=>'btn btn-default fa fa-plus')); ?>
	</h2>



   	    <form id="mobForm" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body">

                    <!-- Input Icons -->
                    <div class="row">



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




  <div class="panel heading-border">
   <div class="panel-body">

<div class="table-responsive">
  <table class="table table-striped" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <th><?=Yii::t('main', 'Päivämäärä')?></th>
  <th><?=Yii::t('main', 'Klo. ajaat')?></th>
  <th><?=Yii::t('main', 'Työntekijä')?></th>
  <th><?=Yii::t('main', 'Kohde')?></th>
  </tr>
  </thead>
  <?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_hyvaksymattomat',
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



$(document).delegate(".lahetaNamat","click",function(){
	$('#mobileTable input:checkbox').each(function () {
           if (this.checked) {

		var thisFor = $(this).attr('for');

	        $.ajax({
	           url: 'laheta_valitsemmat?id='+thisFor,
	           /*type: "POST",
	           data: { id : thisFor },*/
	           success: function(data){
			console.log(data);
	           }
	        });

           }
	});
	window.location.reload();
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

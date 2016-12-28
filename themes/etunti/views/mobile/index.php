<?php
/* @var $this MobileController */
/* @var $dataProvider CActiveDataProvider */

?>


        <!-- begin: .tray-center -->
        <div class="tray-center">


        <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-phone"></i> <?php echo Yii::t('main', 'TUNNIT'); ?> <span class="klo"></span>
		<?php echo CHtml::link('',Yii::app()->request->baseUrl.'/index.php/mobile/create',array('class'=>'btn btn-default fa fa-plus')); ?>
	</h2>



   	    <form id="mobForm" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">

                        <div class="section">
                          <label class="field select">


				<?php
		   		$site = Yii::app()->createController('Site');
		   		$tyontekiatLista = $site[0]->tyontekiatListaNoMulti( 
						'tekijaPaaSivulla', // name
						'gui-input', //class
						null, // id
						Yii::app()->session['tekijaPaaSivulla'], //selected
						1 // aktiivinen
				);
				echo $tyontekiatLista;
				?>


                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>


                        <div class="section">
                          <label class="field prepend-icon">

			    <!-- Autocomplete -->
			    <?php
	   			$site = Yii::app()->createController('Site');
				$mod = 'Asiakkaat';
				$sarake = 'yrityksen_nimi';
				$placeholder = 'Asiakas';
				if(isset($_POST[$sarake])) 			$postvalue = $_POST[$sarake]; 
				else if(isset(Yii::app()->session[$sarake])) 	$postvalue = Yii::app()->session[$sarake]; 
				else $postvalue='';				
		 	        $site[0]->autocompleteFor($mod, $sarake, $placeholder, $postvalue);
			    ?>
			    <!-- Autocomplete -->

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
                            </label>
                          </label>
                        </div>

                      </div>
                      <div class="col-md-2">

                        <div class="section">
                          <label class="field prepend-icon">

			    <!-- Autocomplete -->
			    <?php
	   			$site = Yii::app()->createController('Site');
				$mod = 'Mobile';
				$sarake = 'kohde_kannasta';
				$placeholder = 'Osoite';
				if(isset($_POST[$sarake])) 			$postvalue = $_POST[$sarake]; 
				else if(isset(Yii::app()->session[$sarake])) 	$postvalue = Yii::app()->session[$sarake]; 
				else $postvalue='';				
		 	        $site[0]->autocompleteFor($mod, $sarake, $placeholder, $postvalue);
			    ?>
			    <!-- Autocomplete -->

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
                            </label>
                          </label>
                        </div>

                        <div class="section">
                          <label class="field select">


   <?php
   $list = array();
   $l = Valikkoot::model()->findAll(" select_type='siivous' ",array('order' => "select_type"));

    echo '<select class="gui-input" name="siivousPaaSivulla">';
    if(isset(Yii::app()->session['siivousPaaSivulla']))
    {
       echo '<option value="'.Yii::app()->session['siivousPaaSivulla'].'">'.Yii::app()->session['siivousPaaSivulla'].'</option>';

    } else {
       echo '<option value="">'.Yii::t('main', 'Kohteen työnimike').'</option>';
    }

       echo '<option value="">Kaikki</option>';

    foreach($l as $key=>$val){
    echo '<option value="'.$val->value.'">'.$val->value.'</option>';

    }
    echo '</select>';
   ?>

                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>

                      </div>
                      <div class="col-md-2">

                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="fromP" id="from" class="gui-input datepickerFI" value="<?php if(isset(Yii::app()->session['fromP'])) echo date('d.m.Y', strtotime(Yii::app()->session['fromP'])); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>

                        <div class="section">
                          <label class="field select">


   <?php
   $list = array();
   $l = Valikkoot::model()->findAll(" select_type='tyoryhma' ",array('order' => "select_type"));

    echo '<select class="gui-input" name="tyontekijanRyhma">';
    if(isset(Yii::app()->session['tyontekijanRyhma']))
    {
       echo '<option value="'.Yii::app()->session['tyontekijanRyhma'].'">'.Yii::app()->session['tyontekijanRyhma'].'</option>';

    } else {
       echo '<option value="">'.Yii::t('main', 'Työntekijän ryhmä').'</option>';
    }

       echo '<option value="">Kaikki</option>';

    foreach($l as $key=>$val){
    echo '<option value="'.$val->value.'">'.$val->value.'</option>';

    }
    echo '</select>';
   ?>

                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>

                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="toP" id="to" class="gui-input datepickerFI" value="<?php if(isset(Yii::app()->session['toP'])) echo date('d.m.Y', strtotime(Yii::app()->session['toP'])); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

		<input type="hidden" id="tunni_status" value="<?php if(isset(Yii::app()->session['tunni_status'])) echo Yii::app()->session['tunni_status']; ?>">
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
			    <select name="tunni_status" id="tunni_status_select" class="gui-input">
			     <option value="kaikki"><?php echo Yii::t('main', 'Kaikki'); ?></option>
			     <option value="3"><?php echo Yii::t('main', 'Työt'); ?></option>
			     <option value="2"><?php echo Yii::t('main', 'Matkat'); ?></option>
			     <option value="10"><?php echo Yii::t('main', 'Lounastauko'); ?></option>
			    </select>
                            <i class="arrow double"></i>
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





<div class="admin-form">
  <div class="panel heading-border">
   <div class="panel-body">
    <div class="row">
     <div id="tb" class="table-responsive"></div>
    </div>
   </div>
  </div>
</div>




  <input type="hidden" id="dataChange" >

  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/asetukset.js"></script>
  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.mask.js"></script>
  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/mobile.js"></script>


<script type="text/javascript">
$(document).ready(function(){

/* koko taulukko päivittä joka 60 sek, ja uuden rivin tarkistaminen on 10 sek kuluttua */

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
var mobnum = getParameterByName('Mobile_page');


$(".haemob").click(function(){
	$("#mobForm").submit();
});


// Send form by ajax
$('#mobForm').on('submit',function(e) {

  $('#tb').html('Odota..');

  $.ajax({
  url: 'index?Mobile_page='+mobnum,
  data:$(this).serialize(),
  type:'POST',
  success:function(data){
  	//console.log(data);
	tableAjax();
	return false;
  },
  error:function(data){
  	console.log(data); 
  }
  });

e.preventDefault(); 
});



function tableAjax(){

   $.ajax({
   url: 'index?Mobile_page='+mobnum,
      type: "POST",
      data: { index_ajax : "true" },
      	success: function(data){
  	  	//console.log(data);
  	  	console.log('tableAjax updated');
	  	$('#tb').html(data);
		$('.myBgColors').addClass(localStorage.getItem('headerSkin'));
      	},
  	error:function(data){
  		console.log(data); 
  	}
   });

}

   setTimeout(function(){tableAjax();},100);
   
   // <-- Start and stop Table update
   var interval = 60000;
   var tableAjaxInterval = setInterval(tableAjax, interval);

   $(document).delegate(".muokkaminen","click",function(){
  	console.log('tableAjax pysähtynyt');
	window.clearInterval(tableAjaxInterval);
   });

   $(document).delegate(".pvmupdate","click",function(){
  	console.log('tableAjax updated');
   	setInterval(tableAjax, interval);
   });

   $(document).delegate("#Kohteet_id","change",function(){
  	console.log('tableAjax updated');
   	setInterval(tableAjax, interval);
   });
   // Start and stop Table update -->



    updateRivi();
    function updateRivi() {
        $.ajax({
           url: 'index_ajax',
           success: function(data){

        var date = new Date();
        var hours = date.getHours() < 10 ? "0" + date.getHours() : date.getHours();
        var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
        var seconds = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();
        time = hours + ":" + minutes + ":" + seconds;


	  	$('.klo').text( time );	
		if(data)
		{
		  if(($("#dataChange").val() != data) & ($("#dataChange").val() != ''))
		  {
                    //console.log("new "+data);
		    var e = data;

		   $.ajax({
		      url: 'index?Mobile_page='+mobnum,
		      type: "POST",
		      data: { index_ajax : "true" },
		      success: function(data){		
			  $('#tb').html(data);	
			  if(e){
			    console.log(e);
			    $('#rivi_'+e).fadeOut(1000).fadeIn(1000).fadeOut(1000).fadeIn(1000);
			  }		
		      }
		   });

		  }
		}
		$("#dataChange").val(data);
           }
        });
    }
    setInterval(updateRivi, "5000");

   if($('#tunni_status').val())
   $('#tunni_status_select').val($('#tunni_status').val());

});
</script>

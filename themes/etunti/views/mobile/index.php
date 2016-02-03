<?php
/* @var $this MobileController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('main', 'Luetut kohteet'),
);


	$this->widget('ext.tooltipster.tooltipster');
?>
<br>



        <!-- begin: .tray-center -->
        <div class="tray-center">


              <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-phone"></i> <?php echo Yii::t('main', 'TUNNIT'); ?> <span class="klo"></span></h2>



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
    $criteria = new CDbCriteria();
    $criteria->order = " tekijan_nimi ASC ";
    $criteria->condition = " aktiivinen=1 ";
    $model = Tyontekijat::model()->findAll($criteria);
    $list = CHtml::listData($model, 'id', 'tekijan_nimi');

    echo '<select class="gui-input" name="tekijaPaaSivulla">';
    if(Yii::app()->session['tekijaPaaSivulla'])
    {
       $tekija = Tyontekijat::model()->findbypk(Yii::app()->session['tekijaPaaSivulla']);
       if(isset($tekija->tekijan_nimi))
       echo '<option value="'.$tekija->id.'">'.$tekija->tekijan_nimi.'</option>';
    } else {
       echo '<option value="kaikki">'.Yii::t('main', 'Työntekijät').'</option>';
    }

       echo '<option value="kaikki">Kaikki</option>';

    foreach($list as $key=>$val){
    echo '<option value="'.$key.'">'.$val.'</option>';

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

   			    <input type="text" class="gui-input" name="etsi_kohteet" value="<?php echo Yii::app()->session['etsi_kohteet']; ?>" placeholder="Osoite">

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="fromP" id="from" class="gui-input datepicker" value="<?php echo Yii::app()->session['fromP']; ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="toP" id="to" class="gui-input datepicker" value="<?php echo Yii::app()->session['toP']; ?>">
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
			     <option value="kaikki">Kaikki</option>
			     <option value="3">Työt</option>
			     <option value="2">Matkat</option>
			     <option value="10">Lounastauko</option>
			    </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="Hae">
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
    <div id="tb" class="table-responsive"></div>
   </div>
  </div>




  <input type="hidden" id="dataChange" >


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

  $.ajax({
  url: location.protocol + "//" + location.host + '/index.php/mobile/index?Mobile_page='+mobnum,
  data:$(this).serialize(),
  type:'POST',
  success:function(data){
  	console.log(data);
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
  url: location.protocol + "//" + location.host + '/index.php/mobile/index?Mobile_page='+mobnum,
      type: "POST",
      data: { index_ajax : "true" },
      	success: function(data){
  	  	//console.log(data);
	  	$('#tb').html(data);
      	},
  	error:function(data){
  		console.log(data); 
  	}
   });

}

   setTimeout(function(){tableAjax();},1500);
   setInterval(tableAjax, "60000");


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

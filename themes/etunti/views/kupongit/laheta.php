<?php

?>



        <!-- begin: .tray-center -->
        <div class="tray-center">


        <h2 class="myBgColors p10"> <i class="fa fa-paper-plane-o"></i> <?php echo Yii::t('main', 'Lähetys'); ?> (Alennuskoodi: <?=$model->kupongin_id?>)
	</h2>



   	    <form id="mobForm" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">


                      <div class="col-md-3">
                        <div class="section">
                          <label class="field prepend-icon">
			    <!-- Autocomplete -->
				<input type="text" name="asiakas" id="asiakas" class="form-control" AUTOCOMPLETE="off">
				<input type="hidden" name="asiakas_id" id="asiakas_id" class="form-control">
				<input type="hidden" name="id" value="<?=$model->id?>">
				<input type="hidden" name="kupongin_id" value="<?=$model->kupongin_id?>">
				<div id="asiakasAutocompleteResult"></div>
			    <!-- Autocomplete -->
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>			
                        </div>
		      </div>


                      <div class="col-md-3 col-md-offset-1">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Lähetä'); ?>">
		      </div>

                    </div>



                </div>
              </div>
            </div>

	    </form>


        <!-- loppu: .tray-center -->
        </div>



<script type="text/javascript">
$(document).ready(function(){

  $('#asiakas').keyup(function(){
	var thisVal = $(this).val();

	if( thisVal.length >= 2 )
	{

	  	 $.ajax({
			url: '../tyovuoroot/asiakas_autocomplete',
			type:'GET',
			async : false,
			data: { "key" : thisVal },
			  success:function(data){
				data = JSON.parse(data);
			  	//console.log(data);
				if(data !== '')
					$('#asiakasAutocompleteResult').html(data).show();
				else
					$('#asiakasAutocompleteResult').html('').show();
			  },
			  error:function(data){
			  	console.log(data);
			  }
	 	});

	}
    });

    $(document).delegate(".asiakasSelecter","click",function(){
	var thisVal = $(this).attr('for');
	$('#asiakas').val($(this).text());
	$('#asiakas_id').val(thisVal);
	$('#asiakasAutocompleteResult').html('').hide();
    });

});
</script>

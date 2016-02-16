        <!-- Header-->
        <header>
            <!-- Container-->
            <div class="container">
                <!-- Row-->
                <div class="row">
                    <!-- Logo-->
                    <div class="col-md-3">
                        <div class="logo">
  			<?php $asetukset=Asetukset::model()->find("id=1"); ?>
  			<img src="<?php echo $asetukset->logon_polkku; ?>" height="<?php echo $asetukset->logon_korkeus; ?>">
                        </div>
                    </div>
                    <!-- End Logo-->

                    <!-- Nav-->
                    <div class="col-md-9 slogan">
                        <!--Voita siivousalan haasteet-->
                    </div>
                    <!-- End Nav-->
                </div>
                <!-- End Row-->
            </div>
            <!-- End Container-->
        </header>
        <!-- End Header-->

<?php

if(isset($_POST['palaute'])){

  $model = AsiakasHyvaksynta::model()->find(" id='".$_POST['id']."' and code='".$_POST['code']."' ");

  if(isset($model['id']))
  {
	$ids = explode(",",$model['ids']);
	foreach($ids as $val)
	{
	    $explVal = explode("_", $val);
	    if(isset($explVal[1]))
	    {

		if($explVal[0] == 'mobile') 
		{
		   Mobile::model()->updatebypk($explVal[1], array('asiakas_hyvaksy'=>'2_'.date("d.m.Y")));
		   //echo $explVal[1].'<br>';
		}

		if($explVal[0] == 'toteutu')
		{
		   Toteutuneet::model()->updatebypk($explVal[1], array('asiakas_hyvaksy'=>'2_'.date("d.m.Y")));
		   //echo $explVal[1].'<br>';
		}

	    }

	}

  AsiakasHyvaksynta::model()->updatebypk($model['id'], array('code'=>'','status'=>2,'selitys'=>$_POST['palaute']));

  }

}


if(!empty($id) and !empty($code) and !empty($domain)) 
{

  $model = AsiakasHyvaksynta::model()->find(" id='".$_GET['id']."' and code='".$_GET['code']."' ");
  if(isset($model['id']))
  {

  echo '
        <!-- Services -->
        <section class="esittely">
            <div class="paddings">
                <div class="container">
                    <!-- Icon Big -->
                    <!-- End Icon Big -->


                        <h1 class="title-subtitle text-center" id="paa">
                              Olet hylkäämässä tunteja. 
			<span>Pyydämme jättämään hylkäämisperusteet.</span>
                        </h1>

<div class="row form palauteDiv">
  <div class="col-sm-6 col-sm-offset-3">
	<form action="#" id="palaute" method="POST">
	<input type="hidden" name="id" id="id" value="'.$id.'">
	<input type="hidden" name="code" id="code" value="'.$code.'">
	<input type="hidden" name="domain" id="domain" value="'.$domain.'">
	<label>'.Yii::t('main','Selitys').'</label>
	<textarea name="palaute" id="palauteText" class="form-control" rows="6"></textarea>
	<br>
	<button class="btn btn-primary" id="tallenna-btn">'.Yii::t('main','Lähetä').'</button>
	</form>
  </div>
</div>


                    <!-- End Titles Heading -->

                </div>
                <!-- End Container-->
            </div>
        </section>        <!-- Services -->
  ';



  } else {

  echo '
        <!-- Services -->
        <section class="esittely">
            <div class="paddings">
                <div class="container">
                    <!-- Icon Big -->
                    <!-- End Icon Big -->
                        <h1 class="title-subtitle text-center">Tämä linkki on vanhentunut.
                            <span>
                              Kiitos.
                            </span>
                        </h1>
                        <hr>
                    <!-- End Titles Heading -->

                </div>
                <!-- End Container-->
            </div>
        </section>        <!-- Services -->
  ';

  }

}
?>



<script type="text/javascript">
$(document).ready(function(){


$("#tallenna-btn").click(function(){
	$("#UusiAsiakasForm").submit();
});


$('#palaute').on('submit',function(e) {

  $("#tallenna-btn").replaceWith("Odota");
  var palauteText = $("#palauteText").val();

    if (palauteText  === '') {
        $('#palauteText').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }


$(this).attr("name");

  $.ajax({
  url:'hylkaa?id='+$("#id").val()+'&code='+$("#code").val()+'&domain='+$("#domain").val(),
  data:$(this).serialize(),
  type:'POST',
  success:function(data){
  console.log(data);
	//alert(data)
	$('#paa').hide('slow');
	$('.palauteDiv').html('<center><h1 class="title-subtitle text-center"><span>Olemme vastaanottaneet hylkäämispyynnön perusteluineen.</span><br>Kiitos!</h1></center>');
	return false;
  },
  error:function(data){

  }
});

e.preventDefault(); 
});


});
</script>


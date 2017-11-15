<?php $this->renderPartial('/site/header'); ?>


<br><br>

<div class="container">



<div class="row">
 <div class="col-sm-offset-3 col-sm-6">
  <div class="panel panel-default">
    <div class="panel-heading"><?=Yii::t('main', 'Aloita Etunnin käyttäminen')?></div>
    <div class="panel-body">

      	<form action="aloita" id="aloita-lomake" method="POST" autocomplete="off">
        <label><?php echo Yii::t('main', 'Yrityksen nimi')?></label>
        <input type="text" id="yrityksen_nimi" name="yrityksen_nimi" class="form-control input-lg" required autofocus>
        <label><?php echo Yii::t('main', 'Y-tunnus')?></label>
        <input type="text" id="yritys_tunnus" name="yritys_tunnus" class="form-control input-lg" required>
        <label><?php echo Yii::t('main', 'Puhelinnumero')?></label>
        <input type="text" id="puhelinnumero" name="puhelinnumero" class="form-control input-lg">
        <label><?php echo Yii::t('main', 'Sähköpostiosoite')?></label>
        <input type="text" id="sahkoposti" name="sahkoposti" class="form-control input-lg" required>
        <label><?php echo Yii::t('main', 'Vahvista sähköpostiosoite')?></label>
        <input type="text" id="sahkoposti2" name="sahkoposti2" class="form-control input-lg" required>
	<br>
	<p><a href="#" data-toggle="modal" data-target="#kehdot">Käyttöehdot</a></p>
        <p><button class="btn btn-lg btn-primary btn-group submit" type="submit"><?php echo Yii::t('main', 'Aloita'); ?></button></p>
    	</form>
	<div id="success"></div>

    </div>
  </div>
 </div>
</div>








<!-- Modal -->
<div id="kehdot" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Käyttöehdot</h4>
      </div>
      <div class="modal-body">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>
        <button type="button" class="btn btn-primary hyvaksyn" data-dismiss="modal">Hyväksyn</button>
      </div>
    </div>

  </div>
</div>



</div>



<script type="text/javascript">
$(document).ready(function(){

  localStorage.clear();

  $(".hyvaksyn").click(function(){
  	localStorage.setItem('kayttoehdot_luettu', true);
  });

  $("#kehdot").click(function(e){
	e.preventDefault();
  });

  $(".submit").click(function(e){

	if( $('#sahkoposti').val() !== $('#sahkoposti2').val() ){
		e.preventDefault();
		alert('Tarkasta sähköpostiosoite.');
		return false;
	}

	if(!localStorage.getItem('kayttoehdot_luettu')){
		e.preventDefault();
		alert('Lue ensin käyttöehdot.');
		return false;
	}

	$('#aloita-lomake').submit();
  });

/*
  $("#yrityksen_nimi").keyup(function(e){
	$.ajax({
	  url: '#',
	  data: { keyup_kirjautumistunnus : $(this).val() },
	  type:'POST',
	  success:function(data){
  		$('#kirjautumistunnus').val(data);
	  },
	  error:function(data){
  		console.log(data); 
	  }
  	});
  });
*/

});
</script>

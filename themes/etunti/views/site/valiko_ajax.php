<?php


	// muokka
	if(isset($_POST['muokkaSelects']) and isset($_POST['id']))
	{
		Valikkoot::model()->updatebypk($_POST['id'], array('value'=>$_POST['value']));
	}

	// deleteFromSelect
	if(isset($_POST['deleteFromSelect']) and $_POST['deleteFromSelect'] == "true"){

       		$criteria = new CDbCriteria();
		$criteria->condition = " select_type = '".$_POST['select_type']."' ";
		$v = Valikkoot::model()->findAll($criteria);
	
		if( count($v) > 1 )
		   Valikkoot::model()->deletebypk($_POST['id']);
		else
		    echo '<script>alert("Viimeinen rivi ei voidaan poistaa");</script>';
	
	}
	// uusi
	if(isset($_POST['uusiRiviSelects']) and  $_POST['uusiRiviSelects'])
	{

		$v = new Valikkoot;
		$v->value=$_POST['value'];
		$v->select_type=$_POST['select_type'];
		$v->save();		

	}


	if($_POST['select_type'] == 'vuosilomat') $selType = 'Vuosilomat';
	//elseif($r->select_type == 'laskun_tilanne') $selType = 'laskun tilanne';
	//elseif($r->select_type == 'AddTvuoro') $selType = 'Ajan välit';
	//elseif($r->select_type == 'tyoajanlaatu') $selType = 'Työajanlaatu';
	//elseif($r->select_type == 'palkkaan_hinnat') $selType = 'Palkkaan hinnat';
	//elseif($r->select_type == 'Ruokatauko') $selType = 'Ruokatauko';
	elseif($_POST['select_type'] == 'tyoehtosopimus') $selType = 'Työehtosopimus';
	elseif($_POST['select_type'] == 'kortit') $selType = 'Kortit';
	elseif($_POST['select_type'] == 'online_varauksen_valmina') $selType = 'online varaus';
	elseif($_POST['select_type'] == 'Palkkausmuoto') $selType = 'Palkkausmuoto';
	elseif($_POST['select_type'] == 'tilanne') $selType = 'Tilanne';
	elseif($_POST['select_type'] == 'aktiivinen') $selType = 'Työssä Aktiivinen';
	elseif($_POST['select_type'] == 'tyoryhma') $selType = 'Työryhma';
	elseif($_POST['select_type'] == 'tyoajanmerkinta') $selType = 'Työajanmerkinta';
	elseif($_POST['select_type'] == 'admin status') $selType = 'Oikeukset';
	elseif($_POST['select_type'] == 'siivous') $selType = 'Siivous tyyppi';
	elseif($_POST['select_type'] == 'asiakas_ryhma') $selType = 'Asiakasryhmä';
	else $selType = $r->select_type;


       	$criteria = new CDbCriteria();
	$criteria->condition = " select_type='".$_POST['select_type']."' ";
	$r = Valikkoot::model()->find($criteria);

	$mod = '

	<input type="hidden" id="select_type" value="'.$_POST['select_type'].'">

  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">'.$selType.'</h4>
      </div>
      <div class="modal-body">
        <p><center>';



	       	$criteria = new CDbCriteria();
		$criteria->select = " value,id,select_type ";
		$criteria->condition = " select_type = '".$_POST['select_type']."' ";
		$v2 = Valikkoot::model()->findAll($criteria);

		foreach($v2 as $u)
		{

		if(isset($_POST['id']) and $_POST['id'] == $u['id'])
		  $success = 'btn-success';
		else
		  $success = '';
	
		$mod .= '
		<div class="row" id="rivi_'.$u->id.'">
		  <div class="form-inline">
			<input type="text" class="input-sm form-control form-group '.$success.'" value="'.$u->value.'" id="m_'.$u->id.'">
			<input type="button" class="btn btn-sm btn-warning muokka" for="m_'.$u->id.'" id="'.$u->id.'" value="M"></button>
			<input type="button" class="btn btn-sm btn-danger deleteFromSelect" id="poista_'.$u->id.'" select_type="'.$u->select_type.'" value="X"></button>
		  </div>
		</div>
		';
		}

		$mod .= '<BR>
		<div class="row">
		  <div class="form-inline">
			<input type="text" class="form-control input-sm form-group" id="u_'.$r->id.'">
			<button class="btn btn-success btn-sm form-group uusi" tyyppi="'.$r->select_type.'" for="u_'.$r->id.'">uusi</button>
		  </div>
		</div>';

	$mod .= '
	</center></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>
        <button type="button" class="btn btn-primary tallenna">Tallenna</button>

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->';


$mod .= '
<script type="text/javascript">
$(document).ready(function(){

  $(".tallenna").click(function(){
	window.location.reload();
  });

  $(".muokka").click(function(){
	var forID = $(this).attr("for");
	var thisID = $(this).attr("id");
	var thisVal = $("#"+forID).val();
	$("#"+forID).val("Hetkinen..");
        $.ajax({
           url: location.protocol + "//" + location.host + "/index.php/site/valiko_ajax",
           type: "POST",
           data: {"muokkaSelects" : "true", "id" : thisID, "value" : thisVal, "select_type" : $("#select_type").val()},
           success: function(html){
		$("#result").html(html);
           }
        });
  });

  $(".deleteFromSelect").click(function(){
	var thisID = $(this).attr("id").split("poista_");
	var select_type = $(this).attr("select_type");
        var r=confirm("Oletko varmaa?")
        if (r)
	{
        $.ajax({
           url: location.protocol + "//" + location.host + "/index.php/site/valiko_ajax",
           type: "POST",
           data: { "deleteFromSelect" : "true", "id" : thisID[1], "select_type" : select_type },
           success: function(html){
		$("#result").html(html);
           }
        });
	}
  });


  $(".uusi").click(function(){
	var forID = $(this).attr("for");
	var select_type = $(this).attr("tyyppi");
	var thisVal = $("#"+forID).val();
        $.ajax({
           url: location.protocol + "//" + location.host + "/index.php/site/valiko_ajax",
           type: "POST",
           data: {"uusiRiviSelects" : "true", "value" : thisVal, "select_type" : select_type},
           success: function(data){
		//console.log(html);
		$("#result").html(data);
           }
        });
  });


});
</script>';
	echo $mod;
?>


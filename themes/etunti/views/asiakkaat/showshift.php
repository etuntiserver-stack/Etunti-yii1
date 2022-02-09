<?php

	$a=Asiakkaat::model()->findbypk($id);
	if(isset($a->id))
		$animi = $a->Fullname;
	else
		$animi = '';

	if(isset($a->id) and !empty($a->sahkoposti))
	$sahkoposti = $a->sahkoposti;
	else
	$sahkoposti = '';
?>

<style>
  #massedit-menu {
    position: absolute;
    margin-top: 8px;
    padding: 8px 8px;
    z-index: 999;
    background-color: whitesmoke;
    border: 2px solid #b3b3b3
  }
</style>

<?php if(!isset($_POST['tulosta'])) : ?>

        <!-- begin: .tray-center -->
        <div class="tray-center">
        <h2 class="myBgColors p15"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'Työvuorot').' - '.$animi.', '.$sahkoposti; ?> 
	<!-- tulostus -->
	<div class="pull-right">
	 <div class="form-inline">
	  <form action="../mobile/tulostus" class="form-group" target="_blank" method="POST">
	    <input type="hidden" name="ext" value="doc">
	    <input type="hidden" name="fileName" value="Raporti">
	    <input type="hidden" name="from" value="<?=$from?>">
	    <input type="hidden" name="to" value="<?=$to?>">
	    <textarea name="html_content" class="form-control" style="display:none"></textarea>
    	    <button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-word-o" aria-hidden="true"></i></button>
	  </form>
	  <form action="../mobile/tulostus" class="form-group" target="_blank" method="POST">
	    <input type="hidden" name="ext" value="xls">
	    <input type="hidden" name="fileName" value="Raporti">
	    <input type="hidden" name="from" value="<?=$from?>">
	    <input type="hidden" name="to" value="<?=$to?>">
	    <textarea name="html_content" class="form-control" style="display:none"></textarea>
    	    <button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
	  </form>
	  <form action="../mobile/tulostus" class="form-group" target="_blank" method="POST">
	    <input type="hidden" name="header" value="<?=$from?>-<?=$to?>">
	    <input type="hidden" name="ext" value="pdf">
	    <input type="hidden" name="fileName" value="Raporti">
	    <input type="hidden" name="from" value="<?=$from?>">
	    <input type="hidden" name="to" value="<?=$to?>">
	    <textarea name="html_content" class="form-control" style="display:none"></textarea>
    	    <button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
	  </form>
	 </div>
	</div>
	<!-- tulostus -->
	</h2>



   	    <form id="mobForm" action="#" class="form-inline" method="GET">
   	    <input type="hidden" name="id" value="<?=$id?>">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body">

                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input datepickerFI" name="from" value="<?php echo date('d.m.Y', strtotime($from)); ?>" >

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
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
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="Hae">
		      </div>

          <!-- Shift mass edit menu button. -->
          <div class="col-md-3">
            <!-- <button class="btn-info" data-toggle="collapse" data-target="#massedit-menu" aria-expanded="false" aria-controls="toggle-menu">Muokkaa valittuja vuoroja</button> -->
            <input id="massedit-menu-btn" type="button" class="btn btn-info btn-lg haemob btn-block myBgColors" value="Muokkaa valittuja vuoroja"
              disabled="disabled" data-toggle="collapse" data-target="#massedit-menu" aria-expanded="false" aria-controls="toggle-menu"
              title="Avaa tästä massamuokkausvalikko, jolla voit tehdä muokkauksia kaikille valituille vuoroille samanaikaisesti.">


              <div style="position:relative">
                <div id="massedit-menu" class="collapse">
                  <label for="Tyovuoroot_peruutettu">Peruutettu Merkintä</label>
                  <select id="massedit-cancel-type" class="form-control" style="width:100%">
                    <option value="">Valitse</option>
                    <option value="0">Ei peruutettu</option>
                    <option value="1">Peruutettu</option>
                    <option value="2">Peruutettu laskutettava</option>
                    <option value="3">Peruutettu, laskutetaan välineet 9,90€</option>
                    <option value="4">Peruutettu, laskutetaan välineet 19,90€</option>
                  </select>
                  <input id="massedit-cancel-btn" type="button" class="mt5 btn btn-primary btn-lg haemob btn-block myBgColors" value="Päivitä peruutettu-tila" disabled="disabled">
                </div>
              </div>
          </div>

          <div class="col-md-3">
            <input id="massedit-delete-btn" type="button" class="btn btn-danger btn-lg haemob btn-block myBgColors" value="Poista valitut vuorot" disabled="disabled"
              title="Valitut työvuorot merkitään poistetuiksi mahdollisissa ketjuissa, ja tavalliset työvuorot poistetaan kokonaan.">
          </div>

                    </div>



                </div>
              </div>
            </div>

	    </form>


        <!-- loppu: .tray-center -->
        </div>

<?php else :  ?>
	<link rel="stylesheet" type="text/css" href="css/pdf_table.css">

<style>
.tb .col1{ width: 10%; }
.tb .col2{ width: 40%; }
.tb .col3{ width: 40%; }
.tb .col4{ width: 10%; }
</style>


        <h2 ><?php echo Yii::t('main', 'Työvuorot').' - '.$animi; ?> 
	</h2>
	<h4><?php echo date("d.m.Y",strtotime($_POST['from'])).' - '.date("d.m.Y",strtotime($_POST['to'])); ?></h4>
<?php endif; ?>

<br>

  <div class="panel heading-border">
   <div class="panel-body">

<div class="table-responsive tb" id="tableContent">
  <table class="table table-striped" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <th><input id="showshift-select-all" type="checkbox" title="<?= Yii::t('Main', 'Valitse kaikki') ?>" /></th>
  <th><?php echo Yii::t('main', 'Muokkaa'); ?></th>
  <th><?php echo Yii::t('main', 'Päivä'); ?></th>
  <th><?php echo Yii::t('main', 'Aika'); ?></th>
  <th><?php echo Yii::t('main', 'Kesto'); ?></th>
  <th><?php echo Yii::t('main', 'Osoite'); ?></th>
  <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
  <th><?php echo Yii::t('main', 'Tietoja'); ?></th>
  <th><?php echo Yii::t('main', 'Tila'); ?></th>
  </tr>
  </thead>
  <?php 
	foreach($dataAll as $arr){
		$tv = $this->renderPartial('_showshift',array(
			'data' => $arr['data'],
			'this_id' => $arr['this_id'],
			'this_pvm' => $arr['this_pvm'],
			'this_tid' => $arr['this_tid']
		), true);
		echo $tv;
	}
  ?>
  </table>
</div>

   </div>
  </div>

<?php if(isset($reserved) && count($reserved) > 0) :?>
<h4>Varaus tilassa olevat työvuorot</h4>
<div class="panel heading-border">
  <div class="panel-body">
    <div class="table-response tb">
      <table class="table table-striped">
        <thread class="myBgColors">
        <tr>
          <th><?php echo Yii::t('main', 'Muokkaa'); ?></th>
          <th><?php echo Yii::t('main', 'Päivä'); ?></th>
          <th><?php echo Yii::t('main', 'Aika'); ?></th>
          <th><?php echo Yii::t('main', 'Kesto'); ?></th>
          <th><?php echo Yii::t('main', 'Osoite'); ?></th>
          <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
          <th><?php echo Yii::t('main', 'Tietoja'); ?></th>
          <th><?php echo Yii::t('main', 'Tila'); ?></th>
          </tr>
        </thread>
        <?php
          foreach($reserved as $tv_arr) {
            echo $this->renderPartial("_reserved", [
              "data" => $tv_arr["data"],
              "this_id" => $tv_arr["this_id"],
              "this_pvm" => $tv_arr["this_pvm"],
              "this_tid" => $tv_arr["this_tid"]
            ]);
          }
        ?>
      </table>
    </div>
  </div>
</div>
<?php endif; ?>

<script type="text/javascript">
$(document).ready(function(){
  $(".submitForm").on('click', function(e){
	$('.mobileTable').addClass('table-bordered');
	$(this).prev('textarea').val($('#tableContent').html());
	$(this).closest('form').submit();
	e.preventDefault();
  });

  /** @type (Object) List of selected IDs, populated when an action is fired. */
  let ids = [];

  /** Select/unselect all for mass disabling and other mass edits. */
  $(':checkbox#showshift-select-all').change(function() {

    // Also enable or disable mass edit button here instead of triggering the onChange event for each checkbox.
    if (this.checked) {
      $(':checkbox.massedit-checkbox').prop('checked', true);
      $('#massedit-menu-btn').prop('disabled', false);
      $('#massedit-delete-btn').prop('disabled', false);
    } else {
      $(':checkbox.massedit-checkbox').prop('checked', false);
      $('#massedit-menu-btn').prop('disabled', true);
      $('#massedit-delete-btn').prop('disabled', true);
      $('#massedit-menu').collapse('hide');
    }
  });

  /**
   * If checkbox is checked, enable mass edit button. If unchecked, check if
   * there are any checked boxes, and if not, disable the button.
   */
  $(':checkbox.massedit-checkbox').change(function(e) {

    if (this.checked) {
      // Enable mass edit menu button.
      $('#massedit-menu-btn').prop('disabled', false);
      $('#massedit-delete-btn').prop('disabled', false);
    } else {
      // Check each checkbox. If none are checked, disable mass edit button. Otherwise, enable it.
      if ($(':checkbox.massedit-checkbox:checked').length <= 0) {
        $('#massedit-menu-btn').prop('disabled', true);
        $('#massedit-menu').collapse('hide');
        $('#massedit-delete-btn').prop('disabled', true);
      }
    }

    // Mass check/uncheck should not fire this event, but just in case, stop further triggers.
    e.stopImmediatePropagation();
  });

  /** Enable/disable mass cancel button based on choice (none = disabled). */
  $('#massedit-cancel-type').change(function() {
    $('#massedit-cancel-btn').prop('disabled', $(this).val() == '');
  });

  /**
   * Start an action like mass cancel or delete. This does specific actions like
   * disabling action buttons when another action is underway.
   *
   * When starting action, this function also builds the list of selected IDs,
   * and validates it, not continuing if no selections are made, or other error.
   *
   * @returns (Boolean) True if action was started; otherwise, false (like when no selections).
   */
  const startAction = function() {

    // Get all checked selector checkboxes, map their value and convert to array.
    ids = $(':checkbox.massedit-checkbox:checked').map((i,e) => { return $(e).val(); }).toArray();

    // Validate ids and type before performing request.
    if (ids.length == 0) {
      // No selected IDs. This could be form modification that broke the code, has to be checked.
      console.log(`Error before mass edit operation: ids array is empty (could not find checked boxes).`);
      alert('Virhe: Ei valittuja työvuoroja. Jos tämä ei pidä paikkaansa, ota yhteys ylläpitoon.');
      return false;
    } else {
      // Everything is well, disable action buttons.
      $('#massedit-cancel-btn').prop('disabled', true);
      $('#massedit-delete-btn').prop('disabled', true);
      return true;
    }
  };

  /**
   * Stops an action like mass cancel or delete. This does specific actions like
   * re-enabling the action buttons and de-selecting all selected checkboxes.
   *
   * Based on the operation, the right side result text is also updated.
   *
   * @param (Boolean) finished If true, view should be updated based on the other parameters.
   * @param (String) action Performed action (currently: cancel, delete).
   */
  const stopAction = function(finished = false, action = null) {
    
    // Check if the operation finished or not. If it did, the selections are reset
    // and action buttons are disabled. Otherwise, selections are preserved.
    if (finished === true) {

      // Operation finished. Clear selections and disable operation buttons.
      $('#showshift-select-all').prop('checked', false);
      $(':checkbox.massedit-checkbox').prop('checked', false);
      $('#massedit-cancel-btn').prop('disabled', true);
      $('#massedit-delete-btn').prop('disabled', true);

      // Hide the dialog menu in case it's open, whatever the action.
      $('#massedit-menu').collapse('hide');

      // Update the result text on the right side of each row.
      if (action == 'cancel') {
        let cancelResultText = (function(type) {
          switch (type) {
            case '1': return 'Peruutettu';
            case '2': return 'Peruutettu Laskutettava';
            case '3': return "Peruutettu, laskutetaan välineet 9,90€";
            case '4': return "Peruutettu, laskutetaan välineet 19,90€";
            default: return '';
          }
        })($('#massedit-cancel-type').val());
        ids.forEach((val) => {
          $(`#massedit-${val} td.peruutettu span b`).text(cancelResultText);
        });
      } else if (action == 'delete') {
        ids.forEach((val) => {
          $(`#massedit-${val}`).hide();
        });
      } else {
        alert("Sisäinen virhe: Annettu toiminto on viallinen toiminnon päätösvaiheessa. Näkymää ei voida päivittää.");
      }

      // Empty the global id array to avoid caching bugs
      ids = [];
    } else {

      // Operation didn't finish, the selections are not reset. Re-enable buttons.
      $('#massedit-cancel-btn').prop('disabled', false);
      $('#massedit-delete-btn').prop('disabled', false);
    }
  };

  /**
   * Send mass cancel request.
   */
  $('#massedit-cancel-btn').click(function(e) {
    // Prevent default action, if any.
    e.preventDefault();

    // Disable the operations buttons and populate selected IDs.
    if (!startAction()) {
      return;
    }

    // Get cancel type, which should be between 0 and 2. (0: not canceled, 1: canceled, 2: canceled & to be billed)
    const cancelType = $('#massedit-cancel-type').val();

    // Validate type before performing request.
    if ($.inArray(cancelType, ['0', '1', '2']) == -1) {
      // Invalid selection. Operation is not performed, and action is canceled.
      console.log(`Error before mass edit operation: cancel type "${cancelType}" is invalid (must be between 0 and 2).`);
      alert('Virhe: Viallinen valinta massaperuutukselle.');
      stopAction(false);
      return false;
    } else {

      // Request mass edit via AJAX.
      $.ajax(`${location.protocol}//${location.host}/index.php/tyovuoroot/massedit`, {

        type: 'POST',
        data: {
          'actions': ['cancel'],
          'cancel_type': cancelType,
          'ids': ids
        },

        error: function (xhr, status, error) {
          console.log(xhr.responseText);
          alert(`Sisäinen virhe: ${xhr.responseText}. Jos vika jatkuu, ota yhteys ylläpitoon.`);
        },

        success: function (data) {

          console.log(`Received response, length: ${data.length}:\n${data}`);

          // Try parse response JSON.
          let parsed = null;
          try {
            parsed = JSON.parse(data);
          } catch (e) {
            console.log(`Failed to parse response JSON. Error: ${e}`);
          }

          if (typeof (parsed) != "object") {

            // Parsing failed. Notify log and let it go.
            console.log("Parsed data is unusable (not an object).");
            alert('Pyynnössä tapahtui virhe. Palvelimen palauttamaa vastausta ei voitu lukea (viallinen JSON). Jos vika jatkuu, ota yhteys ylläpitoon.');

          } else if (parsed.length == 0) {

            // Data is empty; this means something is very wrong. TODO
            console.log("Invalid response (empty response).");
            alert('Pyynnössä tapahtui virhe. Palvelimen palauttama vastaus on viallinen (tyhjä). Jos vika jatkuu, ota yhteys ylläpitoon.');

          } else {

            // Parsed data is usable. First, notify about any errors.
            if ("errors" in parsed) {
              let errors = parsed.errors.join("\r\n");
              console.log(`Errors from mass edit operation: ${errors}`);
              alert(`Toiminnossa tapahtui virhe:\r\n${errors}`);
            }

            // Notify about performed actions, even if errors occured.
            if ("result" in parsed) {
              console.log(`Result from mass edit operation: ${parsed.result}`);
              alert(parsed.result);
            }
          }

          location.reload();
        },

        complete: function() {
          // Stop the action, which enables the buttons and de-selects all IDs.
          stopAction(true, 'cancel');
        }
      });
    }
  });

  /**
   * Send mass delete request.
   */
  $('#massedit-delete-btn').click(function(e) {
    // Prevent default action, if any.
    e.preventDefault();

    // Disable the operations buttons and populate selected IDs.
    if (!startAction()) {
      return;
    }

    if (!confirm('<?= Yii::t('main', 'Haluatko varmasti poistaa valitut työvuorot?') ?>')) {
      return false;
    }

    // Request mass delete via AJAX. This uses the same method, as all mass "edits".
    $.ajax(`${location.protocol}//${location.host}/index.php/tyovuoroot/massedit`, {

      type: 'POST',
      data: {
        'actions': ['delete'],
        'ids': ids
      },

      error: function (xhr, status, error) {
        console.log(xhr.responseText);
        alert(`Sisäinen virhe: ${xhr.responseText}. Jos vika jatkuu, ota yhteys ylläpitoon.`);
      },

      success: function (data) {

        console.log(`Received response, length: ${data.length}:\n${data}`);

        // Try parse response JSON.
        let parsed = null;
        try {
          parsed = JSON.parse(data);
        } catch (e) {
          console.log(`Failed to parse response JSON. Error: ${e}`);
        }

        if (typeof (parsed) != "object") {

          // Parsing failed. Notify log and let it go.
          console.log("Parsed data is unusable (not an object).");
          alert('Pyynnössä tapahtui virhe. Palvelimen palauttamaa vastausta ei voitu lukea (viallinen JSON). Jos vika jatkuu, ota yhteys ylläpitoon.');

        } else if (parsed.length == 0) {

          // Data is empty; this means something is very wrong. TODO
          console.log("Invalid response (empty response).");
          alert('Pyynnössä tapahtui virhe. Palvelimen palauttama vastaus on viallinen (tyhjä). Jos vika jatkuu, ota yhteys ylläpitoon.');

        } else {

          // Parsed data is usable. First, notify about any errors.
          if ("errors" in parsed) {
            let errors = parsed.errors.join("\r\n");
            console.log(`Errors from mass delete operation: ${errors}`);
            alert(`Toiminnossa tapahtui virhe:\r\n${errors}`);
          }

          // Notify about performed actions, even if errors occured.
          if ("result" in parsed) {
            console.log(`Result from mass delete operation: ${parsed.result}`);
            alert(parsed.result);
          }
        }

        location.reload();
      },

      complete: function() {
        // Stop the action, which enables the buttons and de-selects all IDs.
        stopAction(true, 'delete');
      }
    });
  });

  // Initialize the massedit menu toggle, so that collapse(hide) does not
  // initialize it, therefore showing it.
  $('#massedit-menu').collapse({toggle: false});
});
</script>

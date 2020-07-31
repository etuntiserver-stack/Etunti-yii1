<!--
  Piilotettu lista kohteen omasiistijöistä, joka voidaan sijoittaa kohteen
  kortille ja asiakkaan/työvuoron kortille.

  Vaaditaan kohde ID; muuten, ei tehdä mitään.
-->

<?php if (isset($kohde_id)): ?>

<?php
// If div id is specified, use it instead of the default one. It should always be unique anyway.
$div_id = (!empty($div_id)) ? $div_id : "omasiistijat_{$kohde_id}";

// Check if custom placeholder div ID is provided. Otherwise, use default.
$placeholder_id = (!empty($placeholder_id)) ? $placeholder_id : 'omasiistijat_kohde';
?>

<style>
  .omasiistijat-painike {
    width: 100%;
    padding: 4px 6px;
    margin-top: 12px;
    border: 1px solid darkgray;
    border-radius: 3px;
    background-color: #f5f8fa;
    text-align: left;
    overflow-x: hidden;
  }

  .omasiistijat-painike:hover {
    background-color: #e6e9eb;
  }
</style>

<!-- Output collapse button with the formed text. -->
<button class="omasiistijat-painike" type="button" data-toggle="collapse" data-target="#<?= $div_id; ?>"><b>Näytä omasiistijät</b></button>
<br><br>

<!-- Hidden element that holds the target location (kohde) ID. This is initially
     provided from the renderPartial call, but may be modified externally in
     order to change the target, e.g. in shift view (työvuoronäkymä). -->
<div id="<?= $placeholder_id ?>" style="display:none"><?= $kohde_id ?></div>

<!-- Form the hidden box containing the workers that have been to this location. -->
<div id="<?= $div_id; ?>" class="omasiistijat-collapse collapse">
  <div class="well well-sm">
      <?php
      // foreach ($workers_query as $result) {
      //   echo CHtml::link("{$result[1]} {$result[2]}", ['tyontekijat/update', 'id' => $result[0]]) . '<br>';
      // }
      ?>
  </div>
</div>

<script>
  $(function() {

    // Hook to the 'show.bs.collapse' event of .collapse element to load workers
    // via AJAX when the element is clicked and opened.
    $('#<?= $div_id; ?>').on('show.bs.collapse', function(e) {

      // Hide any other collapsed list.
      $('.omasiistijat-collapse.collapse.in').collapse('hide');

      const targetId = $('#<?= $placeholder_id ?>').text();
      const workersDivId = '<?= $div_id; ?>';

      // Request list of workers that have been to this location.
      $.ajax(`${location.protocol}//${location.host}/index.php/kohteet/omasiistijat_ajax`, {

        type: 'POST',
        data: {
          id: targetId
        },

        error: function(xhr, status, error) {
          $(`#${workersDivId} .well`).html(`Pyynnössä tapahtui virhe: ${xhr.responseText}`);
          console.log(`(Omasiistijähaku kohteelle ${targetId}) Error: ${xhr.responseText}`);
        },

        success: function(data) {

          console.log(`(Omasiistijähaku kohteelle ${targetId}) Received response, length: ${data.length}`);

          // Try parse response JSON.
          let parsed = null;
          try {
            parsed = JSON.parse(data);
          } catch (e) {
            console.log(`(Omasiistijähaku kohteelle ${targetId}) Error: Failed to parse response JSON. Error: ${e}\nResponse data: ${data}`);
            return;
          }

          if (typeof(parsed) != "object") {

            // Parsing failed. Notify log and let it go.
            console.log(`(Omasiistijähaku kohteelle ${targetId}) Error: Parsed data is unusable (not an object).`);

          } else if (parsed.length == 0) {

            // Data is empty. Notify user.
            $(`#${workersDivId} .well`).empty();
            $(`#${workersDivId} .well`).text('Tällä kohteella ei ole omasiistijöitä.');

          } else {

            // Everything is normal; output received workers list.
            $(`#${workersDivId} .well`).empty();
            parsed.forEach((item, index) => {
              $(`#${workersDivId} .well`).append($(`<a href="/index.php/tyontekijat/update?id=${item['id']}">${item['tekijan_nimi']} ${item['sukunimi']}</a><br>`));
            });
          }
        }
      })
    });
  });
</script>

<?php endif; ?>
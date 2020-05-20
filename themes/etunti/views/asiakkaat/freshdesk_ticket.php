<?php

// Check for errors; partial ticket data is required when style is 'collapse'.
if ($style == 'collapse') {
  if (empty($partial_ticket['id'])) { // ensure that partial ticket data was provided
    $style = 'error';
    $error_text = (!empty($error_text)) ? $error_text :
      "virhe: tukipyynnön tiedot puuttuvat. jos vika jatkuu, ota yhteys ylläpitoon.";
    echo '<p><b>(virhe: tukipyynnön tiedot puuttuvat. jos vika jatkuu, ota yhteys ylläpitoon.)</b></p>';
    $style = null;
  }
} else {
  if (empty($ticket_id) || !is_numeric($ticket_id)) { // ensure that ticket ID was provided
    echo '<p><b>(virhe: annettu tukipyynnön tunniste (id) on viallinen. jos vika jatkuu, ota yhteys ylläpitoon.)</b></p>';
    $style = null;
  }
}

// var_dump($ticket_id ?? 0, $partial_ticket ?? []);exit;

// Draw default style 'collapse' (conversation listing with stacked boxes.)
if ($style == 'collapse') {

  /** @var Freshdesk object */
  $freshdesk = Yii::createComponent('Freshdesk');

  // Generate a link to the given ticket in Freshdesk.
  // $ticket_link = $freshdesk->getTicketUrl($ticket_id);

  // Specify ID for the div. This should be unique, there's many tickets.
  $div_id = "fdticket-{$partial_ticket['id']}";

  // Build text for the ticket button, which will open the collapsible data.
  $ticket_link_text = '';

  // Add ticket subject.
  if (!empty($partial_ticket['subject']))
    $ticket_link_text .= "<b>{$partial_ticket['subject']}</b>";

  // Continue prefix with ticket ID. (not necessary)
  // if (!empty($partial_ticket['id']))
  //   $ticket_link_text .= " ({$partial_ticket['id']})";

  // Add status to the link text (avoid obscure errors with this check).
  if (!empty($partial_ticket['status'])) {

    // Get unified status text for this status from the Freshdesk component.
    $ticket_status_text = $freshdesk->getStatusText($partial_ticket['status']);

    switch ($partial_ticket['status']) {

        // Open
      case 2:
        $ticket_link_text .= " <span class='text text-warning'><b>($ticket_status_text)</b></span>";
        break;

        // Resolved
      case 4:
        $ticket_link_text .= " <span class='text text-success'>($ticket_status_text)</span>";
        break;

        // Closed
      case 5:
        $ticket_link_text .= " <span class='text text-dark'>($ticket_status_text)</span>";
        break;

        // Answered (3), Waiting on customer (6), Waiting for third party (7)
      default:
        $ticket_link_text .= " <span class='text text-primary'>($ticket_status_text)</span>";
    }
  }

  // If available, format the created_at date.
  $fd_suffix_opened = false;
  if (!empty($partial_ticket['created_at'])) {
    $ticket_link_text .= ' (' . date('d.m.Y', strtotime($partial_ticket['created_at']));
    $fd_suffix_opened = true;
  }

  // If available, format the updated_at date.
  if (!empty($partial_ticket['updated_at'])) {
    $ticket_link_text .= $fd_suffix_opened ? ', ' : ' (';
    $ticket_link_text .= 'päivitetty: ' . date('d.m.Y H:i', strtotime($partial_ticket['updated_at']));
    $fd_suffix_opened = true;
  }

  // Close parentheses.
  if ($fd_suffix_opened)
    $ticket_link_text .= ')';

  // Previous link; safekeeping it here.
  // echo CHtml::link($ticket_link_text, $ticket_link, ['target' => '_blank', 'style' => 'color:inherit;']) . '<br>';

?>

  <!-- Output collapse button with the formed text. -->
  <button type="button" data-toggle="collapse" data-target="#<?= $div_id; ?>"><?= $ticket_link_text ?></button>

  <!-- Form the hidden box. When collapsed, ticket data is fetched via AJAX. -->
  <div id="<?= $div_id; ?>" class="collapse">
    <div class="well well-sm">
      <!-- htmlentities(json_encode($partial_ticket)); -->
    </div>
  </div>

<?php
}

// Notify about possible errors.
if ($style == 'error') {
  if (!empty($error_text)) $error_text = rtrim($error_text, ". \t\n\r\0\x0B");
  if (empty($error_text)) $error_text = 'tukipyynnön haku epäonnistui';
  echo "<p><b>(virhe: $error_text. jos vika jatkuu, ota yhteys ylläpitoon.)</b></p>";
}

?>

<script>
  $(function() {

    // Hook to the 'show.bs.collapse' event of .collapse element to load ticket
    // data from the API when the element is clicked and opened.
    $('#<?= $div_id; ?>').on('show.bs.collapse', function(e) {

      const ticketId = '<?= $partial_ticket['id']; ?>';
      const ticketDivId = '<?= $div_id; ?>';
      return;

      $.ajax(`${location.protocol}//${location.host}/index.php/asiakkaat/freshdesk_ticket`, {

        type: 'POST',
        data: {
          ticket: ticketId
        },

        error: function (xhr, status, error) {
          $(`#${ticketDivId} .well`).html(`(pyynnössä tapahtui virhe: ${xhr.responseText})`);
          console.log(`(Ticket ID ${ticketId} request) Error: ${xhr.responseText}`);
        },

        success: function(data) {

          console.log(`(Ticket ID ${ticketId} request) Received response, length: ${data.length}:\n${data}`);

          // Try parse response JSON.
          let parsed = null;
          try {
            parsed = JSON.parse(data);
          } catch (e) {
            console.log(`(Ticket ID ${ticketId} request) Error: Failed to parse response JSON. Error: ${e}\nResponse data: ${data}`);
            return;
          }

          if (typeof (parsed) != "object") {

            // Parsing failed. Notify log and let it go.
            console.log(`(Ticket ID ${ticketId} request) Error: Parsed data is unusable (not an object).`);

          } else if (parsed.length == 0) {

            // Data is empty. Notify log and let it go.
            console.log(`(Ticket ID ${ticketId} request) Error: Received empty response.`);

          } else if ("error_text" in parsed) {

            // Errors happened during request.
            console.log(`(Ticket ID ${ticketId} request) Error: Error in response: ${parsed.error_text}\nResponse data: ${data}`);

          } else {

            // Everything is normal; output received ticket data.
            console.log(`(Ticket ID ${ticketId} request) Request finished without problems.`);
            $(`#${ticketDivId} .well`).html(`<p>${parsed.subject}</p>`);

            // $.each(parsed, function (i, t) {
            //   if (typeof (t) != "object") {
            //     console.log(`(Ticket ID ${ticketId} request) Invalid content inside response data (not an object): ${t}`);
            //   } else {
            //     drawTicket(t.id, t, t.unique_external_id || 0);
            //   }
            // });
          }
        }
      })
    });
  });
</script>
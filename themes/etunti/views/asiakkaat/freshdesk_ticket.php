<?php

/** @var Freshdesk */
$freshdesk = Yii::createComponent('Freshdesk');

if ($freshdesk->isDisabled()) {
  throw new \Exception('Freshdesk on pois päältä tällä domainilla.');
}

// Specify default and valid styles. This list will be appended to later.
$style = empty($style) ? null : $style;
static $default_style = 'collapse';

// ---- Valid styles ----
// 'collapse': ticket is rendered inside a collapsible AJAX box.
// 'raw' (disabled): server response echoed as JSON.
$valid_styles = ['collapse'];

// Default to 'collapse' (JSON output) when style is not specified or invalid.
if (!in_array($style, $valid_styles))
  $style = $default_style;

// If style is 'collapse', ticket data is fetched only when ticket is clicked.
// Otherwise, ticket data should be fetched now.
if ($style == 'collapse') {

  // Ensure that partial ticket data was provided.
  if (empty($partial_ticket['id']))
    throw new \Exception("virhe: tukipyynnön tiedot puuttuvat. jos vika jatkuu, ota yhteys ylläpitoon.");

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

?>

<script>
  $(function() {

    // Hook to the 'show.bs.collapse' event of .collapse element to load ticket
    // data from the API when the element is clicked and opened.
    $('#<?= $div_id; ?>').on('show.bs.collapse', function(e) {

      const ticketId = '<?= $partial_ticket['id']; ?>';
      const ticketDivId = '<?= $div_id; ?>';

      $.ajax(`${location.protocol}//${location.host}/index.php/asiakkaat/freshdesk_ticket`, {

        type: 'POST',
        data: {
          id: ticketId
        },

        error: function (xhr, status, error) {
          $(`#${ticketDivId} .well`).html(`Pyynnössä tapahtui virhe: ${xhr.responseText}`);
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
            $(`#${ticketDivId} .well`).html(`<p>${parsed.ticket.subject}</p>`);

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



<?php

//* Previous code for loading ticket through the API; keeping this code here
//* in case it is needed again. For now, this view is using another controller
//* action through AJAX to get the ticket data when required.

// Ensure that ticket ID was provided.
// if (empty($ticket_id) || !is_numeric($ticket_id)) {
  //   throw new \Exception('Viallinen pyyntö: tukipyynnön ID ei annettu.');
  // echo '<p><b>(virhe: annettu tukipyynnön tunniste (id) on viallinen. jos vika jatkuu, ota yhteys ylläpitoon.)</b></p>';
  // $style = null;
// }

// // Ticket data is requested; fetch it using the API.
// $headers = null;
// $response = $freshdesk->viewTicket($ticket, ['conversations', 'requester'], $headers);
// $has_errors = false;
// $error_text = null;

// // Check for errors in the response.
// if (isset($response['errors'])) {

//   // Errors in response; build error text.
//   $error_text = "Tukipyynnön tietojen hakeminen epäonnistui. Palvelimen palauttamat viestit: '{$response['description']}'";
//   $has_errors = true;

//   // Errors are usually in an array; make sure to avoid errors.
//   if (is_array($response['errors'])) {
//     $error_text .= "\n\nVirheet:\n";

//     // Create a line per each error.
//     foreach ($response['errors'] as $error) {
//       if (!empty($error['message']))
//         $error_text .= "{$error['message']}";
//       if (!empty($error['field']))
//         $error_text .= " ({$error['field']})";
//       if (!empty($error['code']))
//         $error_text .= " -- Virhekoodi: {$error['code']}";
//       $error_text .= "\n";
//     }
//   }
// }

// // Check if the server returned 404, meaning that the ticket was not found.
// if (false !== strpos($headers['http_code'] ?? '', '404')) {

//   // Ticket not found; set error text.
//   $error_text = "Tukipyyntöä ei löytynyt Freshdeskistä. Annettu tunniste on viallinen.";
//   $has_errors = true;
// }

// // Render ticket when style != 'raw' (not required yet).
// // if ($style != 'raw')
// //   return $this->render('freshdesk_ticket', [...]);

// // Output results.
// if ($has_errors) {
//   echo json_encode([
//     'headers' => $headers,
//     'response' => $response,
//     'error_text' => $error_text
//   ]);
// } else {
//   echo json_encode([
//     'headers' => $headers,
//     'ticket' => $response
//   ]);
// }
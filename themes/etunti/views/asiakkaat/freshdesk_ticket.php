<?php

/** @var Freshdesk */
$freshdesk = Yii::createComponent('Freshdesk');

if ($freshdesk->isDisabled()) {
  throw new \Exception('Freshdesk on pois päältä tällä domainilla.');
}

// Ensure that partial ticket data was provided.
if (empty($partial_ticket['id']))
  throw new \Exception("virhe: tukipyynnön tiedot puuttuvat. jos vika jatkuu, ota yhteys ylläpitoon.");

// Generate a link to the given ticket in Freshdesk.
$ticket_link = $freshdesk->getTicketUrl($partial_ticket['id']);

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
    case 2: // Open
      $ticket_link_text .= " <span class='text text-warning'><b>($ticket_status_text)</b></span>";
      break;
    case 4: // Resolved
      $ticket_link_text .= " <span class='text text-success'>($ticket_status_text)</span>";
      break;
    case 5: // Closed
      $ticket_link_text .= " <span class='text text-dark'>($ticket_status_text)</span>";
      break;
    default: // Answered (3), Waiting on customer (6), Waiting for third party (7)
      $ticket_link_text .= " <span class='text text-primary'>($ticket_status_text)</span>";
  }
}

// If available, format the created_at date.
if (!empty($partial_ticket['created_at'])) {
  $ticket_link_text .= "<br>" . date('d.m.Y', strtotime($partial_ticket['created_at']));
  $fd_suffix_opened = true;
}

// If available, format the updated_at date.
if (!empty($partial_ticket['updated_at'])) {
  $ticket_link_text .= ' (päivitetty: ' . date('d.m.Y H:i', strtotime($partial_ticket['updated_at'])) . ')';
}

?>

<style>
  .ticket-btn {
    width: 80%;
    padding: 4px 6px;
    border: 1px solid darkgray;
    border-radius: 8px;
    background-color: #f5f8fa;
    text-align: left;
    overflow-x: hidden;
  }

  .ticket-btn:hover {
    background-color: #e6e9eb;
  }

  /* Main container for entries. */
  .fd {
    padding: 12px;
    margin: 0px 12px 10px 12px;
    width: 80% !important;
    max-width: 915px;
    border-radius: 12px;
  }

  /* Client-side specific (customer). */
  .fd-client {
    background-color: #c5f9ed1f;
    border: 1px solid #70959d6b;
  }

  /* Agent-side (assignee). */
  .fd-agent {
    background-color: #ecffd5;
    border: 1px solid #696a526b;
    float: right !important;
  }

  /* Header text. */
  .fd-head {
    color: #3b3f4f;
    margin: 0px;
    font-weight: bold;
  }

  /* Small text under header (and right link). */
  .fd-head-sub {
    color: #777777;
    /* text-muted */
    margin: 0 10px 0 0;
    font-size: 70%;
    float: left;
  }

  /* Right link. */
  .fd-external-link {
    color: #777777;
    /* text-muted */
    margin: 0px;
  }

  /* Entry body text. */
  .fd-body {
    margin-top: 10px;
    padding: 4px;
    color: #1b1a20d1;
  }
</style>

<!-- Output collapse button with the formed text. -->
<button class="ticket-btn" type="button" data-toggle="collapse" data-target="#<?= $div_id; ?>">
  <?= $ticket_link_text ?>
</button>
<br><br>

<!-- Form the hidden box. When collapsed, ticket data is fetched via AJAX. -->
<div id="<?= $div_id; ?>" class="collapse">
  <div class="well well-sm"></div>
</div>

<script>
  $(function() {

    String.prototype.f = function() {
      let s = this,
        i = arguments.length;

      if (i == 1 && Array.isArray(arguments[0])) {
        return s.f(...arguments[0]);
      }

      while (i) {
        s = s.replace(new RegExp('\\{' + i-- + '\\}', 'gm'), arguments[i]);
      }

      return s;
    };

    const date = function(dateString, long = true) {
      const date = new Date(dateString);
      if (long) {
        return '{1}.{2}.{3} {4}:{5}'.f(
          date.getDate(),
          date.getMonth() + 1,
          date.getFullYear(),
          date.getHours(),
          date.getMinutes()
        );
      } else {
        return '{1}.{2}.{3}'.f(
          date.getDate(),
          date.getMonth() + 1,
          date.getFullYear()
        );
      }
    };

    const getStatusText = function(status) {
      switch (status) {
        case 2:
          return 'Avoin (vastaamatta)';
        case 3:
          return 'Vastattu';
        case 4:
          return 'Ratkaistu';
        case 5:
          return 'Suljettu';
        case 6:
          return 'Odottaa Asiakasta';
        case 7:
          return 'Odottaa Tietoa';
        default:
          return 'Avoin';
      }
    };

    const translateTicketType = function(type) {
      switch (type) {
        case 'Question':
          return 'Kysymys';
        case 'Incident':
          return 'Tapaus';
        case 'Problem':
          return 'Ongelma';
        case 'Feature Request':
          return 'Toimintopyyntö';
        case 'Refund':
          return 'Hyvitys';
        case 'Request':
        default:
          return 'Pyyntö';
      }
    };

    // Hook to the 'show.bs.collapse' event of .collapse element to load ticket
    // data from the API when the element is clicked and opened.
    $('#<?= $div_id; ?>').on('show.bs.collapse', function(e) {

      // Hide any other collapsed ticket.
      $('.collapse.in').collapse('hide');

      const ticketId = '<?= $partial_ticket['id']; ?>';
      const ticketDivId = '<?= $div_id; ?>';

      // Request new ticket data.
      $.ajax(`${location.protocol}//${location.host}/index.php/asiakkaat/freshdesk_ticket`, {

        type: 'POST',
        data: {
          id: ticketId
        },

        error: function(xhr, status, error) {
          $(`#${ticketDivId} .well`).html(`Pyynnössä tapahtui virhe: ${xhr.responseText}`);
          console.log(`(Ticket ID ${ticketId} request) Error: ${xhr.responseText}`);
        },

        success: function(data) {

          console.log(`(Ticket ID ${ticketId} request) Received response, length: ${data.length}`);

          // Try parse response JSON.
          let parsed = null;
          try {
            parsed = JSON.parse(data);
          } catch (e) {
            console.log(`(Ticket ID ${ticketId} request) Error: Failed to parse response JSON. Error: ${e}\nResponse data: ${data}`);
            return;
          }

          if (typeof(parsed) != "object") {

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
            $(`#${ticketDivId} .well`).empty();

            let conversationEntries = 0;
            const data = parsed.ticket;

            // Draw header.
            $(`#${ticketDivId} .well`)
              .append($('<div class="row"/>')
                .append($('<div class="col-md-12"/>')
                  .append($('<div class="fd fd-client"/>')
                    .append($('<span class="fd-head"/>').text('({1}) {2}'.f(++conversationEntries, data.subject)))
                    .append($('<a/>').attr({
                      'href': '<?= $ticket_link; ?>',
                      'target': '_blank',
                      'class': 'fd-external-link pull-right fa fa-external-link-square',
                      'data-toggle': 'tooltip',
                      'data-placement': 'top',
                      'title': 'Avaa Freshdeskissä'
                    }))
                    .append($('<br/>'))
                    .append($('<span class="fd-head-sub"/>').text((data.created_at == data.updated_at) ? date(data.created_at) : '{1} (päivitetty {2})'.f(date(data.created_at), date(data.updated_at))))
                    .append($('<span class="fd-head-sub"/>').html('ID: <b>{1}</b>'.f(data.id)))
                    .append($('<span class="fd-head-sub"/>').html('Tyyppi: <b>{1}</b>'.f(translateTicketType(data.type))))
                    .append($('<span class="fd-head-sub"/>').html('Tila: <b>{1}</b>'.f(getStatusText(data.status))))
                    .append($('<br/>'))
                    .append($('<div class="fd-body"/>').html(data.description))
                  )
                )
              );

            // Draw conversation.
            if ('conversations' in data) {
              data.conversations.forEach(function(entry) {
                $(`#${ticketDivId} .well`)
                  .append($('<div class="row"/>')
                    .append($('<div class="col-md-12"/>')
                      .append($('<div class="{1}"/>'.f((entry.incoming) ? 'fd fd-client' : 'fd fd-agent'))
                        .append($('<span class="fd-head"/>').text('({1}) {2}:'.f(++conversationEntries, entry.from_email)))
                        .append($('<br/>'))
                        .append($('<span class="fd-head-sub"/>').text((entry.created_at == entry.updated_at) ? date(entry.created_at) : '{1} (päivitetty {2})'.f(date(entry.created_at), date(entry.updated_at))))
                        .append($('<span class="fd-head-sub"/>').html('Vastaus ID: <b>{1}</b>'.f(entry.id)))
                        .append($('<br/>'))
                        .append($('<div class="fd-body"/>').html(entry.body))
                      )
                    )
                  );
              });
            }
          }
        }
      })
    });
  });
</script>
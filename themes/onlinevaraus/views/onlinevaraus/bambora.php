<?php

require dirname(__FILE__) . '/../../../../lib/bambora-payform/bambora_payform_loader.php';

/*
	If you are not using composer, use the following file to load all the class files instead of composer's autoload.php above
	require dirname(__FILE__) . '/../lib/bambora_payform_loader.php';
*/

$asetukset = Asetukset::model()->findByPk(1);
$private_key = $asetukset->bambora_private_key ?? '';
$api_key = $asetukset->bambora_api_key ?? '';
if (empty($private_key) || empty($api_key)) {
  echo 'Bambora Payform tunnukset puuttuvat!';
  exit;
}


if (!isset($_SESSION['onlinevaraus']['modelTV']))
  $this->redirect(array('index'));
$tv = Tyovuoroot::model()->findbypk($_SESSION['onlinevaraus']['modelTV']);
if (!isset($tv->id)) {
  echo 'Virhe tilauksessa (1455)';
  exit;
}


$payForm = new Bambora\PayForm($api_key, $private_key);
$payment_return = '';

if (isset($_GET['action'])) {
  if ($_GET['action'] == 'auth-payment') {

    $serverPort = (isset($_SERVER['SERVER_PORT']) && ($_SERVER['SERVER_PORT'] != 80 &&  $_SERVER['SERVER_PORT'] != 433)) ? ':' . $_SERVER['SERVER_PORT'] : '';
    // $returnUrl = strstr("http" . (!empty($_SERVER['HTTPS']) ? "s" : "") . "://" . $_SERVER['SERVER_NAME'] . $serverPort . $_SERVER['REQUEST_URI'], '?', true) . "?return-from-pay-page";
    $returnUrl = Yii::app()->createAbsoluteUrl('onlinevaraus/maksettu', array(), 'http') . "?return-from-pay-page";
    $method = isset($_GET['method']) ? $_GET['method'] : '';

    $amount = $amount * 100;
    $reference = $_SESSION['onlinevaraus']['onlinevarausID'];
    $message = 'Työvuoro ' . $tv->pvm . ', ' . $tv->alku . ' - ' . $tv->loppu;

    $payForm->addCharge(array(
      // 'order_number' => 'onlinevaraus_' . $reference /* . time() */,
      'order_number' => $reference,
      'amount' => $amount,
      'currency' => 'EUR'
    ));

    $payForm->addCustomer(array(
      'firstname' => $etu_suku_nimet,
      'lastname' => '',
      'address_street' => $osoite,
      'address_city' => $kaupunki,
      'address_zip' => $postinumero
    ));

    $payForm->addProduct(array(
      'id' => $reference,
      'title' => $message,
      'count' => 1,
      'pretax_price' => intval($amount / 1.24),
      'tax' => 1,
      'price' => $amount,
      'type' => 1
    ));

    if ($method === 'iframe')
      $returnUrl .= '&iframe';

    $paymentMethod = array(
      'return_url' => $returnUrl,
      'notify_url' => $returnUrl,
      'lang' => 'fi'
    );

    if ($method === 'embedded')
      $paymentMethod['type'] = 'embedded';
    else
      $paymentMethod['type'] = 'e-payment';

    if (isset($_GET['selected'])) {
      $paymentMethod['selected'] = array(strip_tags($_GET['selected']));
    }

    $payForm->addPaymentMethod($paymentMethod);

    try {
      $result = $payForm->createCharge();

      if ($result->result == 0) {
        if ($method === 'iframe') {
          header('Cache-Control: no-cache');
          echo json_encode(array(
            'url' => $payForm::API_URL . '/token/' . $result->token
          ));
        } else if ($method === 'embedded') {
          echo json_encode(array(
            'token' => $result->token
          ));
        } else {
          header('Location: ' . $payForm::API_URL . '/token/' . $result->token);
        }
      } else {
        $error_msg = 'Unable to create a payment. ';

        if (isset($result->errors) && !empty($result->errors)) {
          $error_msg .= 'Validation errors: ' . print_r($result->errors, true);
        } else {
          $error_msg .= 'Please check that api key and private key are correct.';
        }

        exit($error_msg);
      }
    } catch (Bambora\PayFormException $e) {
      exit('Got the following exception: ' . $e->getMessage());
    }
  }

  exit();
}

try {

  $merchantPaymentMethods = $payForm->getMerchantPaymentMethods();
  if ($merchantPaymentMethods->result != 0) {
    exit('Unable to get the payment methods for the merchant. Please check that api key and private key are correct.');
  } else {
    if (isset($_SESSION['onlinevaraus']['onlinevarausID'])) {
      $veroton_hinta = 0;
      if (isset($_SESSION['onlinevaraus']['alv'])) {
        $alv = $_SESSION['onlinevaraus']['alv'];
        $veroton_hinta = ($_SESSION['onlinevaraus']['amount'] / (1 + ($alv / 100)));
        $alv_hinta = $_SESSION['onlinevaraus']['amount'] - $veroton_hinta;
        $veroton_hinta = round((float) str_replace(",", ".", $veroton_hinta), 2);
      }

      $ov = Onlinevaraus::model()->findbypk($_SESSION['onlinevaraus']['onlinevarausID']);
      $ov->tv_id = $_SESSION['onlinevaraus']['modelTV'];
      // $ov->maksun_onnistu_koodi = $result->AUTHCODE;
      $ov->kesto = $kesto;
      $ov->alv = $alv;
      $ov->veroton_hinta = $veroton_hinta;
      $ov->hinta = $_SESSION['onlinevaraus']['amount'];
      $ov->tilauksen_kuvaus = json_encode($_SESSION['onlinevaraus']['tilauksenKuvaus']);
      $ov->save();
    }
  }
} catch (Bambora\PayFormException $e) {
  exit('Got the following exception: ' . $e->getMessage());
}

?>

<style type="text/css">
  a,
  a:hover,
  a:focus {
    text-decoration: none;
  }

  #overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: #000;
    filter: alpha(opacity=50);
    -moz-opacity: 0.5;
    -khtml-opacity: 0.5;
    opacity: 0.5;
    z-index: 1;
  }

  #payment_frame {
    height: 650px;
    width: 500px;
    position: absolute;
    z-index: 2;
    margin-left: -250px;
    left: 50%;
    top: 20px;
  }
</style>

<!-- <div class="container"> -->
<?php if ($payment_return) : ?>
  <div class="row">
    <div class="col-md-12">
      <div class="alert alert-success" role="alert"><?= $payment_return; ?>, <a target="_top" href="index.php">start again</a></div>
    </div>
  </div>
<?php endif; ?>
<div class="row" id="mainpage">
  <div class="col-md-12">
    <?php
    foreach ($merchantPaymentMethods->payment_methods as $pm) {

      // Draw logo link
      echo "<div class=\"col-xs-4\">";
      echo "  <a class=\"img\" href=\"?action=auth-payment&method=button&selected={$pm->selected_value}\">";
      echo "    <img class=\"img-thumbnail\" style=\"margin-bottom: 20px\" alt=\"{$pm->name}\" src=\"{$pm->img}\">";
      echo "  </a>";
      echo "</div>";
    }
    ?>
  </div>
</div>
<!-- </div> -->

<script>
  window.addEventListener('message', function(event) {
    var data = JSON.parse(event.data)

    if (data.valid) {
      var initEmbeddedPayment = $.get("?action=auth-payment&method=embedded")

      initEmbeddedPayment.done(function(data) {
        var response
        try {
          response = $.parseJSON(data)
        } catch (err) {
          alert('Unable to initialize embedded card payment. Please check that api key and private key are correct.')
          return
        }

        var payMessage = {
          action: 'pay',
          token: response.token
        }

        document.getElementById('pf-cc-iframe').contentWindow.postMessage(
          JSON.stringify(payMessage),
          'https://payform.bambora.com'
        )
      })
    }
  });

  // Embedded iframe card form
  $("#inline-form").click(function(e) {
    e.preventDefault()

    var validateMessage = {
      action: "validate"
    }

    document.getElementById('pf-cc-iframe').contentWindow.postMessage(
      JSON.stringify(validateMessage),
      'https://payform.bambora.com/'
    )
  })

  // Open minified card form in iframe
  var card_payment_result = $('.card-payment-result')
  $("#iframe").click(function(e) {
    e.preventDefault()
    var initPayment = $.get("?action=auth-payment&method=iframe")
    initPayment.done(function(data) {
      var response
      try {
        response = $.parseJSON(data)
      } catch (err) {
        card_payment_result.html('Unable to create card payment. Please check that api key and private key are correct.')
        alert('Unable to create card payment. Please check that api key and private key are correct.')
        return
      }
      var overlay = $('<div id="overlay"></div>').appendTo(document.body);
      $('<iframe>', {
        src: response.url + "?minified",
        id: 'payment_frame',
        frameborder: 0,
        scrolling: 'no'
      }).appendTo(document.body);
    })
  })

  //if in iframe, prevent inception
  if (window.self !== window.top)
    $("#mainpage").hide();
</script>

<!-- 
Testitunnukset:

Test card numbers
With test cards you can use any valid expiration date in the future and a random 3 digit CVV code.
Following test cards can be used to test successful payments.
Card Number
Visa: 4012888888881881
Mastercard: 5244024870672677
AmericanExpress: 378748900392307
DinersClub: 36319163670450
NOTE: Tokenized test cards (card_tokens) are automatically removed after 1 month of inactivity.

Error tests: Following test cards will fail with error codes mentioned below.
Card Number 	Error code 	Error explanation
4400000000000008 	null 	Unknown error
4484070000000000 	04 	The card is reported lost or stolen.
4462030000000000 	05 	General decline. The card holder should contact the issuer to find out why the payment failed.
4900000000000003 	51 	Insufficient funds. The card holder should verify that there is balance on the account and the online payments are actived.
4900000000000011 	54 	Expired card.
4900000000000086 	61 	Withdrawal amount limit exceeded.
4917610000000000 	62 	Restricted card. The card holder should verify that the online payments are actived.
4917300800000000 	1000 	Timeout communicating with the acquirer. The payment should be tried again later.

Following test cards can be used to test errors for card token payments.
The cards will return successful response for the initial charge request, so a card token can be registered, but will fail with following errors if the card token is used
Card Number 	Error code 	Error explanation
4242424242424218 	null 	Unknown error
4242424242424242 	04 	The card is reported lost or stolen.
4166676667666746 	05 	General decline. The card holder should contact the issuer to find out why the payment failed.
4444333322221111 	51 	Insufficient funds. The card holder should verify that there is balance on the account and the online payments are actived.
4444333322221129 	54 	Expired card.
4646464646464644 	61 	Withdrawal amount limit exceeded.
4977949494949497 	62 	Restricted card. The card holder should verify that the online payments are actived.
4988438843884305 	1000 	Timeout communicating with the acquirer. The payment should be tried again later.

Following test cards can be used to test the 3-D Secure for card token payments
The cards will return successful response for the initial charge request, so a card token can be registered and then will trigger the 3-D Secure flow when the card token is used. These will only work for Customer initiated transactions (CIT).
Card Number 	3-D Secure return value 	Explanation
4154210000000001 	Y 	3-D Secure was used
4571740000000002 	A 	3-D Secure was attempted but not supported by the card issuer or the card holder is not participating
4002620000000005 	N 	3-D Secure was not successfully completed and the payment fails

Test bank credentials
Nordea credentials
Username 	Password 	Confirmation code
123456 	1111 	Any 4 digits
Osuuspankki credentials
Username 	Password 	Confirmation code
123456 	7890 	Not needed

 -->

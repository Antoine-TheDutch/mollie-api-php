<?php
// subscription_webhook for mollie API v2 By Antoine Adams (antoine@pmo-online.com) (Sep 2020)
// Why this script?
// This script is called by the subscription webhook and needs to be handled differently different than a payment webhook
// With a normal payment webhook we know the customerId (as is was created and given by the payment script)
// With a subscription webhook we only get a payment ID in a POST hence we need to request what customerId and subscriptionId
// the received paymentId belongs too.
// I hope this contribution helps others understand the process, but for clarity I'll name the steps (and the reference scripts)
// 1) Create a customer  (examples/customers/create-customer.php)
// 2) Create first payment (examples/customers/create-customer-first-payment.php)
// 2.a) The script at step 2  uses a webhook script. (this is a different one than this one!!!)
// 3) Create subscription (examples/subscriptions/create-subscription.php)
// 3a) The script at step 3 uses THIS webhook.  (yes.  these are thus 2 webhooks needed)
// I hope my contribution helps others
//--------------------------------

$id = $_REQUEST["id"];                                             // start by getting ID
try {
    require "initialize.php";                                      // Initialize mollie API
    $payment = $mollie->payments->get($id);                        // Fill $payment with info from the ID we got
    $subscription_id = $payment->subscriptionId;                   //  Extract the subscriptionId
    $customer_id = $payment->customerId;                           // Extract the customerId

    // The subscription will only call for the following things:  isPaid, isExpired, isFailed, isCanceled

   if ($payment->isPaid())  {
   // do stuff when things are paid
   } elseif ($payment->isExpired()) {
    /* Here you write actions that need to happen when it is expired */ 
   } elseif ($payment->isFailed()) {
    /* Here you write actions that need to happen when it is failed */
   } elseif ($payment->isCanceled()) {
    /* Here you write actions that need to happen when it is canceled */
   }
} catch (\Mollie\Api\Exceptions\ApiException $e) {
    echo "API call failed: " . \htmlspecialchars($e->getMessage());
}
?>

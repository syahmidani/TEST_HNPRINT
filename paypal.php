<?php
 
// show all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 
// initialize CURL
$ch = curl_init();
 
// set path to PayPal API to generate token
// remove "sandbox" from URL when in live
curl_setopt($ch, CURLOPT_URL, 'https://api-m.sandbox.paypal.com/v1/oauth2/token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
// write your own client ID and client secret in following format:
// {client_id}:{client_secret}
curl_setopt($ch, CURLOPT_USERPWD, 'AeI6giOtJtkjOH_lPraZ62E5NNDpZgFeq663NK763eLrhlgmbKYrUQdkgn42JSxi2EQSGxD14W1rYV4E:EATD8qlT5x0g-F-YgyER60gTwJho0_leMWhn7YiAPBPqoE5c1lV41AOuRv6GK41DeWZvw5mgxhEpS92i');
 
// set headers
$headers = array();
$headers[] = 'Accept: application/json';
$headers[] = 'Accept-Language: en_US';
$headers[] = 'Content-Type: application/x-www-form-urlencoded';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
 
// call the CURL request
$result = curl_exec($ch);
 
// check if there is any error in generating token
if (curl_errno($ch))
{
    echo json_encode([
        "status" => "error",
        "message" => curl_error($ch)
    ]);
    exit();
}
curl_close($ch);
 
// the response will be a JSON string, so you need to decode it
$result = json_decode($result);
 
// get the access token
$access_token = $result->access_token;
 
// we only need the second part of orderID variable from client side
$payment_token_parts = explode("-", $_POST["orderID"]);
$payment_id = "";

 
if (count($payment_token_parts) > 1)
{
    $payment_id = $payment_token_parts[1];
}
 
// initialize another CURL for verifying the order
$curl = curl_init();
 
// call API and send the payment ID as parameter
curl_setopt($curl, CURLOPT_URL, 'https://api-m.sandbox.paypal.com/v2/checkout/orders/' . $payment_id);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
 
// set headers for this request, along with access token
$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'Authorization: Bearer ' . $access_token;
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
 
// executing the request
$result = curl_exec($curl);
 
// check if there is any error
if (curl_errno($curl))
{
    echo json_encode([
        "status" => "error",
        "message" => "Payment not verified. " . curl_error($curl)
    ]);
    exit();
}
curl_close($curl);
 
// get the response JSON decoded
$result = json_decode($result);
 
// you can use the following if statement to make sure the payment is verified
// if ($result->status == "COMPLETED")
 
// send the response back to client
echo json_encode([
    "status" => "success",
    "message" => "Payment verified.",
    "result" => $result
]);
exit();
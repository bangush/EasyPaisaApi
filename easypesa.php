
<html>
<title>Esay Pay</title>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
img {
  width: 80%;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}
</style>
</head>
<body>

<?php 

$hashRequest = '';
$hashKey = $_GET['hashKey']; // generated from easypay account
$storeId=$_GET['storeId'];
$amount=$_GET['amount'];
$postBackURL=$_GET['postBackURL'];
$orderRefNum=$_GET['orderRefNum'];

$autoRedirect=0;
$paymentMethod=$_GET['paymentMethod'];


///starting encryption///
$paramMap = array();
$paramMap['amount']  = $amount;
$paramMap['autoRedirect']  = $autoRedirect;
//$paramMap['emailAddr']  = $emailAddr;

//$paramMap['mobileNum'] =$mobileNum;
$paramMap['orderRefNum']  = $orderRefNum;
$paramMap['paymentMethod']  = $paymentMethod;
$paramMap['postBackURL'] = $postBackURL;
$paramMap['storeId']  = $storeId;
// exit;
//Creating string to be encoded
$mapString = '';
foreach ($paramMap as $key => $val) {
      $mapString .=  $key.'='.$val.'&';
}
$mapString  = substr($mapString , 0, -1);
// Encrypting mapString
function pkcs5_pad($text, $blocksize) {
      $pad = $blocksize - (strlen($text) % $blocksize);
      return $text . str_repeat(chr($pad), $pad);
}

$alg = MCRYPT_RIJNDAEL_128; // AES
$mode = MCRYPT_MODE_ECB; // ECB

$iv_size = mcrypt_get_iv_size($alg, $mode);
$block_size = mcrypt_get_block_size($alg, $mode);
$iv = mcrypt_create_iv($iv_size, MCRYPT_DEV_URANDOM);

$mapString = pkcs5_pad($mapString, $block_size);
$crypttext = mcrypt_encrypt($alg, $hashKey, $mapString, $mode, $iv);
$hashRequest = base64_encode($crypttext);

// end encryption;
echo "
<img src='asset/loading_view.gif' alt='Paris' style='width:20%;'>
<form action='https://easypay.easypaisa.com.pk/easypay/Index.jsf' method='POST' id='easyPayStartForm'>
<input name='storeId' value='$storeId' hidden = 'true'/>
<input name='amount' value='$amount' hidden = 'true'/>
<input name='postBackURL' value='$postBackURL' hidden = 'true'/>
<input name='orderRefNum' value='$orderRefNum' hidden = 'true'/>

<input type='hidden' name='autoRedirect' value='$autoRedirect' >
<input type ='hidden' name='paymentMethod' value='$paymentMethod'>
<input type ='hidden' name='merchantHashedReq' value='$hashRequest'>
<button hidden = 'true' type='submit'>Submit</button>

</form>";

echo "<script> document.getElementById('easyPayStartForm').submit();</script>";
?>



</body>
</html>


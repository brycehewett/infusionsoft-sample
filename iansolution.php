<?php

session_start();
require_once './vendor/autoload.php';


$infusionsoft = new \Infusionsoft\Infusionsoft(array(
	'clientId' => 'G0IfUajhA6U0MYqiFlN8AQCYOoo6KyGJ',
	'clientSecret' => '7HuC8MfmkDEpGWt4',
	'redirectUri' => 'http://localhost:8888/infusiontest/solution.php',
));


// By default, the SDK uses the Guzzle HTTP library for requests. To use CURL,
// you can change the HTTP client by using following line:
// $infusionsoft->setHttpClient(new \Infusionsoft\Http\CurlClient());

// If the serialized token is available in the session storage, we tell the SDK
// to use that token for subsequent requests.
if (isset($_SESSION['token'])) {
	$infusionsoft->setToken(unserialize($_SESSION['token']));
}

// If we are returning from Infusionsoft we need to exchange the code for an
// access token.
if (isset($_GET['code']) and !$infusionsoft->getToken()) {
	$infusionsoft->requestAccessToken($_GET['code']);

    // Save the serialized token to the current session for subsequent requests
    $_SESSION['token'] = serialize($infusionsoft->getToken());
}


		function updateStoreCode($infusionsoft) {
			$contactId = $_REQUEST["contactId"];
				$numberId = array('_numberId');
				$storeCode = array('_StoreCode');
			if(empty($storeCode)) {
			  $data = array('_StoreCode' => '_numberId');
			  $infusionsoft->contacts('xml')->update($contactId, $data);
			}
		}
echo updateStoreCode('233');







if ($infusionsoft->getToken()) {
	try {
	//	$task = updateStoreCode($infusionsoft);
	$task = updateStoreCode($infusionsoft);
	}
	catch (\Infusionsoft\TokenExpiredException $e) {
		// If the request fails due to an expired access token, we can refresh
		// the token and then do the request again.
		$infusionsoft->refreshAccessToken();

		// Save the serialized token to the current session for subsequent requests
		$_SESSION['token'] = serialize($infusionsoft->getToken());

		//$task = updateStoreCode($infusionsoft);
		//$task = retrieveContactdata($infusionsoft);
		$task = updateStoreCode($infusionsoft);
	}
}
else {
	echo '<a href="' . $infusionsoft->getAuthorizationUrl() . '">Click here to authorize</a>';
}

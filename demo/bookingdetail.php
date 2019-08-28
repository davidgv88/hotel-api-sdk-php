<?php
/**
 * Created by PhpStorm.
 * User: vmavromatis
 * Date: 05/09/2017
 * Time: 16:29
 */


require __DIR__ .'/../vendor/autoload.php';

use hotelbeds\hotel_api_sdk\HotelApiClient;
use hotelbeds\hotel_api_sdk\model\Destination;
use hotelbeds\hotel_api_sdk\model\Occupancy;
use hotelbeds\hotel_api_sdk\model\Pax;
use hotelbeds\hotel_api_sdk\model\Rate;
use hotelbeds\hotel_api_sdk\model\Stay;
use hotelbeds\hotel_api_sdk\types\ApiVersion;
use hotelbeds\hotel_api_sdk\types\ApiVersions;
use hotelbeds\hotel_api_sdk\messages\AvailabilityRS;


$reader = new Zend\Config\Reader\Ini();
$config = $reader->fromFile(__DIR__.'/HotelApiClient.ini');
$cfgApi = $config["apiclient"];

$apiClient = new HotelApiClient($cfgApi["url"],
    $cfgApi["apikey"],
    $cfgApi["sharedsecret"],
    new ApiVersion(ApiVersions::V1_2), 
    $cfgApi["timeout"]); //Make sure you use 1.2 for no CC details and 1.0 version for CC details w/ secure URL.

$reference = urldecode($_GET['reference']);

try {
    $bookingDetailRS = $apiClient->BookingDetail($reference);
    echo "<b>Booking Detail Raw Response <a href='https://developer.hotelbeds.com/docs/read/apitude_booking/booking/BookingDetail#bookingdetails-response'>(View Documentation)</a></b><br>";
    echo "<pre>".json_encode($bookingDetailRS->booking, JSON_PRETTY_PRINT)."</pre>";
}
catch (\hotelbeds\hotel_api_sdk\types\HotelSDKException $e) {
    echo "\n" . $e->getMessage() . "\n";
    echo "<br><br>" . $apiClient->getLastRequest();
    //var_dump($e->getMessage());
}


return null;

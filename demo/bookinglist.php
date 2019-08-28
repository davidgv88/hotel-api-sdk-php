<?php
require __DIR__ .'/../vendor/autoload.php';

use hotelbeds\hotel_api_sdk\HotelApiClient;
use hotelbeds\hotel_api_sdk\types\ApiVersion;
use hotelbeds\hotel_api_sdk\types\ApiVersions;

$reader = new Zend\Config\Reader\Ini();
$config = $reader->fromFile(__DIR__.'/HotelApiClient.ini');
$cfgApi = $config["apiclient"];

$apiClient = new HotelApiClient($cfgApi["url"],
    $cfgApi["apikey"],
    $cfgApi["sharedsecret"],
    new ApiVersion(ApiVersions::V1_2), 
    $cfgApi["timeout"]); //Make sure you use 1.2 for no CC details and 1.0 version for CC details w/ secure URL.

$rqBookingList = new \hotelbeds\hotel_api_sdk\helpers\BookingList();
$rqBookingList->start= DateTime::createFromFormat("Y-m-d", '2019-06-01');
$rqBookingList->end= DateTime::createFromFormat("Y-m-d", '2019-06-30');
$rqBookingList->filterType = "CREATION";
$rqBookingList->status = "ALL";
$rqBookingList->from = 1;
$rqBookingList->to = 25;
try {
    $bookingListRS = $apiClient->BookingList($rqBookingList);
    
    if (!$bookingListRS->isEmpty()) {
        //var_dump($bookingListRS->bookings->bookings);
        //die();
        echo"
    <style>
    table {border-collapse:collapse; table-layout:fixed; width:410px;}
   table td {border:solid 1px #c4e3f3; width:200px; word-wrap:break-word;}
    </style>
    ";
        
        echo "<table border='1'><tr><td>";
    
    echo "</td></tr></table>";

    echo "<br><br>";

    echo "<b>Availability Response <a href='https://developer.hotelbeds.com/docs/read/apitude_booking/Availability#response-parameters'>(View Documentation)</a></b><br>";
    echo "<table border='1'>";
    echo "<tr><td>Reference</td><td>creationDate</td><td>status</td><td>view</td></tr>";


    foreach ($bookingListRS->bookings->iterator() as $bookingDetail) {

        //var_dump($bookingDetail);
        //var_dump($hotelData->iterator());
        echo "<tr>";
                echo '<td>'.$bookingDetail->reference.'</td>';
                echo '<td>'.$bookingDetail->creationDate.'</td>';
                echo '<td>'.$bookingDetail->status.'</td>';
                echo '<td><a href="bookingdetail.php?reference='. urlencode($bookingDetail->reference).'">View</a></td>';
        echo "</tr>";
    }
    echo '</table><br><br>';
    }
    
    echo "<b>Booking List Raw Response <a href='https://developer.hotelbeds.com/docs/read/apitude_booking/booking/BookingDetail#bookingdetails-response'>(View Documentation)</a></b><br>";
    echo "<pre>".json_encode($bookingListRS->bookings->toArray(), JSON_PRETTY_PRINT)."</pre>";
}
catch (\hotelbeds\hotel_api_sdk\types\HotelSDKException $e) {
    echo "\n" . $e->getMessage() . "\n";
    echo "<br><br>" . $apiClient->getLastRequest();
    //var_dump($e->getMessage());
}


return null;

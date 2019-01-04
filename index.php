<?php
/*
	Plugin Name: Bookeo
	Plugin URI: https://profiles.wordpress.org/fahadmahmood/#content-plugins
	Description: This plugin holds the calss and all its mehtods 
	Version: 1.1.4
	Author: Samuel Urias
	Author URI: http://www.samuelurias.com
	License: GPL2


	This WordPress plugin is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 2 of the License, or
	any later version.

	This WordPress plugin is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this WordPress plugin. If not, see http://www.gnu.org/licenses/gpl-2.0.html.
*/


class WPBookeo
{

    private $API_KEY = ''; 
    private $SECRET_KEY = '';
    private $BOOKEO_URL = 'https://api.bookeo.com/v2/';
    private $PRODUCT_CODE = ''; // Tours

    private $actualUrl = ''; // This is the actual url that is being used


    public function __construct()
    {

    }


//https://api.bookeo.com/v2/bookings?secretKey=u5KHhbjfp3ac1RnRBO5kIJu5EAtLfELw&type=fixed&apiKey=A4ARN6LN9ATFKC6JHJTWE4156836AMX6163F3FBAD89&startTime=2018-06-14T00:00:00-07:00&endTime=2018-06-15T00:00:00-07:00


    /**
     * The purpose of this functions is to retrieve all the bookings for an account
     * @return void
     */
    public function retrieveBookings()
    {
        echo 'We are into \'' . __FUNCTION__ . '\'' . PHP_EOL;

        $url = $this->BOOKEO_URL . 'bookings';
        $data = array(
            'startTime' => '2018-08-29T00:00:00-07:00',
            'endTime' => '2018-08-29T23:00:00-07:00'
        );
        $data = array_merge($data, $this->arrayCredentials());

        $output = $this->common_curl($url, $data);
        return $output;
    }

    /**
     * The purpose of this function is to return an array of the credentials
     *
     * @return void
     */
    private function arrayCredentials()
    {
        $arrayCredentials = array(
            'apiKey' => $this->API_KEY,
            'secretKey' => $this->SECRET_KEY
        );

        return $arrayCredentials;
    }

    /**
     * The purpose of this function is to make ajax call to the api
     */
    private function common_curl($url, $data = array(), $request_type = 'GET', $sendEverythinByUrl = false, $makeTheRequestWithHeaders = false)
    {
        if ($request_type == 'GET' or $sendEverythinByUrl == true) {
            $url .= '?' . http_build_query($data);
            echo $url . PHP_EOL;
            echo $url . PHP_EOL;
            echo $url . PHP_EOL;
            echo $url . PHP_EOL;
        }


        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $url);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // Headers
        $headers = array(
            'Content-Type: application/json',
            'Accept: application/json'
        );

        if ($request_type != 'GET') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            if ($headers) {
                curl_setopt($ch, CURLOPT_HEADER, 1);
            }
            if (!$sendEverythinByUrl) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // send data in json
            }
        }

        echo PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;
        echo json_encode($data) . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;


        //  echo $url. PHP_EOL;
        // exit;
        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources


        if ($makeTheRequestWithHeaders) {
            echo "inside the headers if";
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            echo PHP_EOL . 'Header Size: ' . $header_size . PHP_EOL;
            $headersReturned = substr($output, 0, $header_size);
            $bodyReturned = substr($output, $header_size);
            $output = array('body' => $bodyReturned, 'headers' => $headersReturned);
        }
        curl_close($ch);

        return $output;
    }

    /**
     * The purspose of this function is to retrieve all the products of an account
     * @return void
     */
    public function retrieveProducts()
    {
        $url = $this->BOOKEO_URL . 'settings/products';
        $data = $this->arrayCredentials();
        $output = $this->common_curl($url, $data);
        return $output;
    }

    public function checkAvailabileSlots($productId, $customStartTime = false)
    {
        echo "Into " . __FUNCTION__ . " ... " . PHP_EOL;
        $url = $this->BOOKEO_URL . 'availability/slots';
        echo $url . PHP_EOL;
        $data = array(
            'productId' => $productId,
            'startTime' => '2018-07-18T00:00:00-00:00',
            'endTime' => '2018-07-20T00:00:00-00:00'

        );
        $data = array_merge($data, $this->arrayCredentials());
        $output = $this->common_curl($url, $data, 'GET', true, false);

        return $output;
    }

    public function checkAvailabileSlotsWithFlexibleTime($productId)
    {
        $url = $this->BOOKEO_URL . 'availability/matchingslots/';
        $data = array(
            'productId' => $productId,
            'startTime' => '2018-07-18T00:00:00-00:00',
            'endTime' => '2018-07-19T00:00:00-07:00'
        );
        $data = array_merge($data, $this->arrayCredentials());

        var_dump($data);
        $output = $this->common_curl($url, $data, 'POST', true);

        return $output;
    }

    public function createBooking()
    {

    }

    public function holds($evnetId, $productId)
    {
        $url = '';
        $url = $this->BOOKEO_URL . 'holds' . '?' . 'apiKey=' . $this->API_KEY . '&secretKey=' . $this->SECRET_KEY;
        echo 'URL: ' . $url . PHP_EOL;


        echo 'productId: ' . $productId . PHP_EOL;
        $data = [
//           'booking' => (object)array(
            'eventId' => $evnetId,
//            'title' => 'Title',
            'customer' => (object)[
                'firstName' => 'John Doe',
                'lastName' => 'Smith',
                'emailAddress' => 'dummy@dummy.com',
                // 'phoneNumbers'  => array('number'=>'123456', 'type'=>'mobile')
            ],
            'participants' => (object)[
                'numbers' => [
                    [
                        'peopleCategoryId' => 'Cadults',
                        'number' => 1,
                    ]
                ]
            ],
            'productId' => $productId
//           )
        ];
        // $data = array_merge($data, $this->arrayCredentials());

        $response = $this->common_curl($url, $data, 'POST', false, true);
        return $response;
    }

    public function getCalculatedPrice()
    {

    }

    /**
     * This function is used to do the 4th step of the example
     * @param $holdId
     * @return array|mixed
     */
    public function getCalculatedFinalPrice($holdId)
    {
        $url = $holdId;
        $data = $this->arrayCredentials();
        $response = $this->common_curl($url, $data, 'GET', false, false);

        return $response;
    }

    /**
     * This function is the 5th step of the example
     */
    public function createBookingFinalStep($holdingId, $evnetId, $productId)
    {
        $url = $this->BOOKEO_URL . 'bookings' . '?' . 'apiKey=' . $this->API_KEY . '&secretKey=' . $this->SECRET_KEY;;
        $data = [
//           'booking' => (object)array(
            'previousHoldId' => trim($holdingId),
            'notifyUsers' => 'true',
            'notifyCustomer' => 'true',
            'eventId' => $evnetId,
//            'title' => 'Title',
            'customer' => (object)[
                'firstName' => 'John Doe',
                'lastName' => 'Smith',
                'emailAddress' => 'dummy@dummy.com',
                // 'phoneNumbers'  => array('number'=>'123456', 'type'=>'mobile')
            ],
            'participants' => (object)[
                'numbers' => [
                    [
                        'peopleCategoryId' => 'Cadults',
                        'number' => 1,
                    ]
                ]
            ],
            'productId' => $productId,
            "initialPayments" => [
                (object)[
                    "reason" => "Initial deposit",
                    "comment" => "This is a custom comment",
                    "amount" => (object)[
                        "amount" => "1",
                        "currency" => "USD"
                    ],
                    "paymentMethod" => "cash"
                ]
            ]
//           )
        ];
        $data = array_merge($data, $this->arrayCredentials());
        $response = $this->common_curl($url, $data, 'POST', false, false);

        return $response;
    }

    public function getActualUrl()
    {
        return $this->actualUrl;
    }

    /**
     * The purpose of this function is to format the startTime and endTime in a
     * format that the api support
     */
    private function formatTime()
    {
    }

    /**
     * The purpose of this function is to append the credentials to the url.
     * This function was created because the credentias were needed
     */
    private function addCredentials($appendSymbol = '&')
    {
        $credentials = '';
        $credentials = $appendSymbol;
        $credentials .= $credentials .= 'apiKey=' . $this->API_KEY . '&secretKey=' . $this->SECRET_KEY;

        return $credentials;
    }

    private function setActualUrl($url)
    {
        $this->actualUrl = $url;
    }

}

$wpBookeo = new WPBookeo();
echo 'This thing is working' . PHP_EOL;
echo '------------------------------------' . PHP_EOL;
echo 'Bookings:';
echo '------------------------------------' . PHP_EOL;
echo $wpBookeo->retrieveBookings();
echo PHP_EOL;

echo '------------------------------------' . PHP_EOL;
echo 'Products:' . PHP_EOL;
echo '------------------------------------' . PHP_EOL;
echo $wpBookeo->retrieveProducts();
echo PHP_EOL;


// Product id: 41568NY436A163F40164F2 // Samuel
// 41568X7UUEU1641E5934D6 //Jeff- Games
echo '------------------------------------' . PHP_EOL;
echo 'Availabilty Slot:' . PHP_EOL;
echo '------------------------------------' . PHP_EOL;
print_r($wpBookeo->checkAvailabileSlots('41568X7UUEU1641E5934D6'));
echo PHP_EOL;
echo "<-----" . PHP_EOL;


echo '------------------------------------' . PHP_EOL;
echo 'Hold' . PHP_EOL;
echo '------------------------------------' . PHP_EOL;
$rrespons = $wpBookeo->holds('41568X7UUEU1641E5934D6_41568AYY9XE164232C8076_2018-07-13', '41568X7UUEU1641E5934D6');
print_r($rrespons);
$hheaders = $rrespons['headers'];
print_r($hheaders);
$position1 = strpos($hheaders, 'Location') + 9;
$position2 = strpos($hheaders, 'Content-Type');
$stringLength = $position2 - $position1;
$locationOfTheHeader = trim(substr($hheaders, $position1, $stringLength));
echo "Location:::::|" . $locationOfTheHeader . "|::::" . PHP_EOL;


echo '------------------------------------' . PHP_EOL;
echo 'Calculated Final Price' . PHP_EOL;
echo '------------------------------------' . PHP_EOL;
$finalPriceResponse = $wpBookeo->getCalculatedFinalPrice($locationOfTheHeader);
echo PHP_EOL . "----->" . PHP_EOL;
print_r($finalPriceResponse);
echo PHP_EOL . "<-----" . PHP_EOL;

$headersId = $finalPriceResponse;
$headersJsonObject = json_decode($headersId);
$headersId = $headersJsonObject->id;
echo "Header id::" . $headersId = $headersJsonObject->id . PHP_EOL;


echo '------------------------------------' . PHP_EOL;
echo 'Booking Creation' . PHP_EOL;
echo '------------------------------------' . PHP_EOL;
$finalBookingResponse = $wpBookeo->createBookingFinalStep($headersId, '41568X7UUEU1641E5934D6_41568AYY9XE164232C8076_2018-07-13', '41568X7UUEU1641E5934D6');
print_r($finalPriceResponse);
?>


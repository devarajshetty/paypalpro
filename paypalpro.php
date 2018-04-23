
<?php
//this file has been added to github
// Include config file
require_once('includes/config.php');


// Store request params in an array
echo $_POST['fname'].'<br/>';
echo $_POST['Lname'].'<br/>';
echo $_POST['Street'].'<br/>';
echo $_POST['City'].'<br/>';
echo $_POST['State'].'<br/>';
echo $_POST['Country_code'].'<br/>';
echo $_POST['Zip_code'].'<br/>';
echo $_POST['Amount'].'<br/>';
echo $_POST['Currency_no'].'<br/>';
echo $_POST['Account_no'].'<br/>';
echo $_POST['Exp_date'].'<br/>';
echo $_POST['Cvv'].'<br/>';
echo $_POST['Cardtype'].'<br/>';
echo $_POST['Amount'];
$request_params = array
                    (
                    'METHOD' => 'DoDirectPayment', 
                    'USER' => $api_username, 
                    'PWD' => $api_password, 
                    'SIGNATURE' => $api_signature, 
                    'VERSION' => $api_version, 
                    'PAYMENTACTION' => 'Sale',                   
                    'IPADDRESS' => $_SERVER['REMOTE_ADDR'],
                    'CREDITCARDTYPE' => $_POST['Cardtype'], 
                    'ACCT' => $_POST['Account_no'],                        
                    'EXPDATE' => $_POST['Exp_date'],           
                    'CVV2' => $_POST['Cvv'], 
                    'FIRSTNAME' => $_POST['fname'], 
                    'LASTNAME' => $_POST['Lname'], 
                    'STREET' => $_POST['Street'], 
                    'CITY' => $_POST['City'], 
                    'STATE' => $_POST['State'],                     
                    'COUNTRYCODE' => $_POST['Country_code'], 
                    'ZIP' => $_POST['Zip_code'], 
                    'AMT' => $_POST['Amount'], 
                    'CURRENCYCODE' => $_POST['Currency_no'], 
                    'DESC' => 'Testing Payments Pro'
                    );

// Loop through $request_params array to generate the NVP string.
$nvp_string = '';
foreach($request_params as $var=>$val)
{
    $nvp_string .= '&'.$var.'='.urlencode($val);   

}
//echo  '<br/>'.$nvp_string;

$curl = curl_init();
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_URL, $api_endpoint);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $nvp_string);
        
        //echo '<br/>'.$curl;

$result = curl_exec($curl);  
//echo '<br/>'.$result;
curl_close($curl);

// Parse the API response
$nvp_response_array = parse_str($result);

// Function to convert NTP string to an array

function NVPToArray($NVPString)
{
    $proArray = array();
    while(strlen($NVPString))
    {
        // name
        $keypos= strpos($NVPString,'=');
        $keyval = substr($NVPString,0,$keypos);
        // value
        $valuepos = strpos($NVPString,'&') ? strpos($NVPString,'&'): strlen($NVPString);
        $valval = substr($NVPString,$keypos+1,$valuepos-$keypos-1);
        // decoding the respose
        $proArray[$keyval] = urldecode($valval);
        $NVPString = substr($NVPString,$valuepos+1,strlen($NVPString));
    }   
   
    return $proArray;
 
}
?>
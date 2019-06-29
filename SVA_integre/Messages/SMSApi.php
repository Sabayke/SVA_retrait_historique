<?php 

namespace Messages;
include "ApiInfos.php";
/**
 * @author BNG
 * 30/05/2018
 */

class SMSApi {
    
    const BASE_URL = 'https://api.orange.com';

    protected $clientId = '';
    
    protected $clientSecret = '';

    protected $token = '';

    protected $verifyPeerSSL = true;

    /**
     * Creates a new smsapi instance. If the user doesn't know his token or doesn't have a
     * token yet, he can leave $token empty and retrieve a token with
     * getTokenFromConsumerKey() method later.
     *
     * @param  array  $config  An associative array that can contain clientId, clientSecret, 
     *                         token, and verifyPeerSSL
     *
     * @return void
     */
    public function __construct($config = null)
    {
        $clientId = is_null($config)? ApiInfos::$CLIENT_ID: $config->getCLIENTID();
        $secret = is_null($config)? ApiInfos::$SECRET_ID: $config->getSECRETID();
       $this->setClientId($clientId);
       $this->setClientSecret($secret);
       $this->getTokenFromConsumerKey();
    }

    
    
    /**
     * Retrieves a token from Orange server, that will be used for all further API calls.
     *
     * @return array
     */
    public function getTokenFromConsumerKey()
    {   
        $url = self::BASE_URL . '/oauth/v2/token';

        $credentials = $this->getClientId() . ':' . $this->getClientSecret();

        $headers = array('Authorization: Basic ' . base64_encode($credentials));

        $args = array('grant_type' => 'client_credentials');

        $response = $this->callApi($headers, $args, $url, 'POST', 200);

        if (!empty($response['access_token'])) {
            $this->setToken($response['access_token']);
        }

        return $response;
    }

    /**
     * Sends SMS.
     *
     * @param  string  $senderAddress    The receiver address in this format:
     *                                   "tel:+22500000000"
     * @param  string  $receiverAddress  The receiver address in this format:
     *                                   "tel:+22500000000"
     * @param  string  $message          The content of the SMS, must not exceed
     *                                   160 characters         
     * @param  string  $senderName       The sender name
     *
     * @return array
     */
    public function sendSms($senderAddress, $receiverAddress, $message, $senderName = '') {
        $url = self::BASE_URL . '/smsmessaging/v1/outbound/' . urlencode($senderAddress) . '/requests';

        $headers = array(
            'Authorization: Bearer ' . $this->getToken(),
            'Content-Type: application/json'
        );

        if (!empty($senderName)) {
            $args = array(
                'outboundSMSMessageRequest' => array(
                    'address'                   => $receiverAddress,
                    'senderAddress'             => $senderAddress,
                    'senderName'                => urlencode($senderName),
                    'outboundSMSTextMessage'    => array(
                        'message' => $message
                    )
                )
            );
        } else {
            $args = array(
                'outboundSMSMessageRequest' => array(
                    'address'                   => $receiverAddress,
                    'senderAddress'             => $senderAddress,
                    'outboundSMSTextMessage'    => array(
                        'message' => $message
                    )
                )
            );
        }
        //var_dump(urlencode("tel:+221774686888"));die();
        return $this->callApi($headers, $args, $url, 'POST', 201, true);
    }

    /**
     *  Calls API Endpoints.
     *
     * @param  array   $headers         An array of HTTP header fields to set
     * @param  array   $args            The data to send
     * @param  string  $url             The URL to fetch
     * @param  string  $method          Whether to do a HTTP POST or a HTTP GET
     * @param  int     $successCode     The HTTP code that will be returned on
     *                                  success
     * @param  bool    $jsonEncodeArgs  Whether or not to json_encode $args
     *
     * @return array   Contains the results returned by the endpoint or an error
     *                 message
     */
    public function callApi($headers, $args, $url, $method, $successCode, $jsonEncodeArgs = false) {
        $ch = curl_init();
    
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);

            if (!empty($args)) {
                if ($jsonEncodeArgs === true) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($args));
                } else {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($args));
                }
            }
        } else /* $method === 'GET' */ {
            if (!empty($args)) {
                curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($args));
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if ($this->getVerifyPeerSSL() === false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        // Make sure we can access the response when we execute the call
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($ch);

        if ($data === false) {
            return array('error' => 'API call failed with cURL error: ' . curl_error($ch));
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
 
        curl_close($ch);

        $response = json_decode($data, true);

        $jsonErrorCode = json_last_error();
        if ($jsonErrorCode !== JSON_ERROR_NONE) {
            return array(
                'error' => 'API response not well-formed (json error code: '
                    . $jsonErrorCode . ')'
            );
        }

        // if ($httpCode !== $successCode) {
        //     $errorMessage = '';

        //     if (!empty($response['error_description'])) {
        //         $errorMessage = $response['error_description'];
        //     } elseif (!empty($response['error'])) {
        //         $errorMessage = $response['error'];
        //     } elseif (!empty($response['description'])) {
        //         $errorMessage = $response['description'];
        //     } elseif (!empty($response['message'])) {
        //         $errorMessage = $response['message'];
        //     } elseif (!empty($response['requestError']['serviceException'])) {
        //         $errorMessage = $response['requestError']['serviceException']['text']
        //             . ' ' . $response['requestError']['serviceException']['variables'];
        //     } elseif (!empty($response['requestError']['policyException'])) {
        //         $errorMessage = $response['requestError']['policyException']['text']
        //             . ' ' . $response['requestError']['policyException']['variables'];
        //     }

        //     return array('error' => $errorMessage);
        // }

        return $response;
    }

    /**
     * Displays how many SMS you can still send.
     *
     * @param  string  $country  The country to filter on (the international 3 digits country)
     * @return array
     */
    private function getBalance($country = ''){
        $url = self::BASE_URL . '/sms/admin/v1/contracts';

        $headers = array('Authorization: Bearer ' . $this->getToken());

        $args = null;

        if (!empty($country)) {
            $args = array('country' => $country);
        }

        return $this->callApi($headers, $args, $url, 'GET', 200);
    }

    /**
     * Dysplays how many SMS you can still Send and duration
     * 
     * @param  string  $country  The country to filter on (the international 3 digits country)
     * @return array
     * 
     */

     public function getSMSBalance($country = ''){
        $balance = $this->getBalance($country);

        if(array_key_exists('error', $balance)){
            return $balance;
        }
        $infos = $balance["partnerContracts"]["contracts"][0]["serviceContracts"];
        $smsRestatnts = $infos[0]['availableUnits'];
        $date_expiration = explode("T", $infos[0]['expires']);
        
        return array(
            "smsRestants" => $smsRestatnts,
            "date_expiration" => $date_expiration
        );
     }

    public static function getInstance(){
        if(!isset($_SESSION['INSTANCE_OF_SMSAPI'])){
            $_SESSION['INSTANCE_OF_SMSAPI'] = new SMSApi();
        }
        return $_SESSION['INSTANCE_OF_SMSAPI'];
    }



    /***%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
     * ************************************************
     *          GETTERs AND SETTERs                   *
     **************************************************
     %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
    /**
     * Get your application.
     *
     * @return  string
     */ 
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Set your application.
     *
     * @param  string  $clientId  your application.
     *
     * @return  self
     */ 
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * Get client Secret. Used to sign/crypt the requests.
     *
     * @return  string
     */ 
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * Set client Secret. Used to sign/crypt the requests.
     *
     * @param  string  $clientSecret  Client Secret. Used to sign/crypt the requests.
     *
     * @return  self
     */ 
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    /**
     * Get the Token will be used for all further API calls.
     *
     * @return  string
     */ 
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set the Token will be used for all further API calls.
     *
     * @param  string  $token  The Token will be used for all further API calls.
     *
     * @return  self
     */ 
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

      /**
     * Get the value of verifyPeerSSL
     */ 
    public function getVerifyPeerSSL()
    {
        return $this->verifyPeerSSL;
    }

    /**
     * Set the value of verifyPeerSSL
     *
     * @return  self
     */ 
    public function setVerifyPeerSSL($verifyPeerSSL)
    {
        $this->verifyPeerSSL = $verifyPeerSSL;

        return $this;
    }

}
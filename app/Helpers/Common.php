<?php

namespace App\Helpers;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Redis;
use Carbon\CarbonInterval;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
//use Illuminate\Support\Facades\Validator;
use Validator;
use Illuminate\Validation\ValidationException;
use App;
use SoapClient;
use SoapFault;

use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use Twilio\Rest\Client;

/**
 * Description of Kellton Tech
 *
 * @author Niraj Kr. Bharti - Kellton Technologies
 */
class Common
{

    /**
     * Method to build response
     * @param type $exception
     * @return type
     */
    public static function responseBuilder($status, $message, $data){
        Self::sendResponse([
            'status'    => $status,
            'message'   => $message,
            'result'    => $data,
        ], $status);

    }
     
    /**
     * Method to show exception page
     * @param type $exception
     * @return type
     */
    public static function showException($exception, $code = '500')
    {
        //setting message
        $message = self::isProduction() ? Config('HttpCodes.message' . $code) : $exception->getMessage();

        $exceptionErrorResponse = [
            "code"    => $code,
            "message" => $message,
            "result"  => []
        ];

        $responseCode = (is_numeric($code) && $code > 0) ? $code : config('HttpCodes.fail');

        (new Response($exceptionErrorResponse, $responseCode))->header('Content-Type', 'application/json')->send();
        exit;

    }

    /**
     * Method to validate Request
     * @param array $post
     * @param array $rules
     */
    public static function validateRequest(array $post, array $rules, $req = NULL)
    {
        $validator = Validator::make($post, $rules);
        
        //Check input parameter validation
        if ($validator->fails()) {
            //Sending Validation Error Message
            $messagesOrErrors = true; // Errors - 1 messages - 0

            // if No validation error
            if($req <> NULL ) { Log::error(['path' => json_encode(['uri' => $req->path(), 'ip' => $req->ip()]), 'request' => json_encode($req->all()), 'error' => json_encode($validator) ]); }

            self:: showValidationError($validator, $messagesOrErrors);
            exit;
        }

        // if No validation error
        if($req <> NULL ) { Log::info(['path' => json_encode(['uri' => $req->path(), 'ip' => $req->ip()]), 'request' => json_encode($req->all()) ]); }

    }

    /**
     * Method to show validate error
     * @param type $validator
     */
    public static function showValidationError($validator, $messagesOrErrors = false)
    {
        $messages = $messagesOrErrors ? $validator->errors() : $validator->messages()->all();
        return Self::responseBuilder(config('HttpCodes.code.bad_request'), "Parameters errors" , $messages);        
    }

    /**
     * Method to send response json
     * @param type $response
     * @return type
     */
    public static function sendResponse(array $response, $code, $req = NULL)
    {
        try {

            //manage logs here
            // self::manageLogs($response , $req);
            if($req <> NULL ) { Log::info(['path' => json_encode(['uri' => $req->path(), 'ip' => $req->ip()]), 'request' => json_encode($req->all()), 'response' => json_encode($response) ]); }
            // end of manage logs here

            return (new Response($response, $code))->send(); die;
        } catch (Exception $ex) {
            if($req <> NULL ) { Log::debug(['path' => json_encode(['uri' => $req->path(), 'ip' => $req->ip()]), 'request' => json_encode($req->all()), 'error' => $ex->message() ]); }
            self::showException($ex->message());
        }
    }

    /**
     * Method to capture logs into manage_logs table
     * @param  \Illuminate\Http\Request  $request 
     * @return  json response    
     */
    public static function manageLogs($response , $req){
       try{
           if(Auth::user()){
             $created_by = auth()->user()->id;
           }else{
            $created_by ='null'; 
           }
           if(!empty($req)){
             $api_name = $req->url();
             $request_data = $req->all();
           }else{
             $api_name = 'null';
             $request_data ='null';
           }

           \App\Manage_logs::insert(['api_name'=>$api_name, 'request_data'=>json_encode($request_data), 'reponse_data'=>json_encode($response) , 'created_by'=>$created_by,  ]);
        } catch (\Illuminate\Database\QueryException $e) {
            $log_data['Error'] = $e->getMessage();
            \Log::Debug($log_data);
        }


    } 

    /**
     * to check Environment is Production or development or staging
     *
     * @return boolean
     */
    public static function isProduction()
    {
        $flag = true;
        // The environment is either local OR staging...
        if (app()->environment('local', 'staging')) {
            $flag = false;
        }
        return $flag;

    }

    /*
     * method to return all required values used for pagination
     *
     */
    public static function paginatorSubsets($limit = '', $page = '')
    {
        $page = ! empty($page) ? $page : 1;
        return [
            ! empty($limit) ? $limit : PAGE_LIMIT,
            $page,
            ($page - 1) * $limit
        ];

    }

    /**
     * Used to set Validation messages
     *
     * @return Array
     */
    public static function validationMessage()
    {
        return [
            'required'      => __('lang.required'),
            'required_with' => __('lang.required'),
            'numeric'       => __('lang.numeric'),
            'unique'        => __('lang.unique'),
            'integer'       => __('lang.integer'),
            'date'          => __('lang.date')
        ];

    }


    /**
     * @function encryptDecrypt
     * @description A common function to encrypt or decrypt desired string
     *
     * @param string $string String to Encrypt
     * @param string $type option encrypt or decrypt the string
     * @return type
     */
    public static function encryptDecrypt($string, $type = 'encrypt')
    {

        if ($type == 'decrypt') {
            #$enc_string = decrypt_with_openssl($string);
            $enc_string = self::base64decryption($string);
        }
        if ($type == 'encrypt') {
            #$enc_string = encrypt_with_openssl($string);
            $enc_string = self::base64encryption($string);
        }
        return $enc_string;

    }

    /**
     * @funciton base64encryption
     * @description will Encrypt data in base64
     *
     * @param type $string
     */
    private static function base64encryption($string)
    {
        return base64_encode($string);

    }



    /**
     * @funciton base64decryption
     * @description will decrypt data in base64
     *
     * @param type $string
     */
    private static function base64decryption($string)
    {
        return base64_decode($string);

    }

    /**
     * Method to get locale time of logged user
     */
    public function localeTime($utcDatetime, $format = ''){
        $gmt    = session()->get('tz');
        //$utcDatetime = Carbon::createFromTimestampUTC($utcDatetime);
        return empty($format) ? $utcDatetime->setTimezone($gmt)->toDayDateTimeString() : $utcDatetime->setTimezone($gmt)->format($format) ;
    }

    /**
    * Method to get locale time of logged user
    */
    public function getDateAsFormat($dateTime, $format = 'Y-m-d'){
        $dateTime = empty($dateTime) ? Carbon::now() : Carbon::parse($dateTime);
        //$utcDatetime = Carbon::createFromTimestampUTC($utcDatetime);
        return $dateTime->format($format);
    }
    
    /**
     * Get Client Detail Limited Data 
     */
    public static function getClientLimitedData() {
        if(auth()->user()){
            // return array_intersect_key(auth()->user()->toArray(), array_flip(["id", "is_active", "isapproved", "client_id" , "company_name", "client_name", "designation", "creditLimit", "email_id", "mobile" , "enable_portal_access"]));
            return array_intersect_key(auth()->user()->toArray(), array_flip(["id", "name", "email"]));
        }
        else {
            throw new Exception(config('HttpCodes.message.400'), config('HttpCodes.code.forbidden'));
        }
    }
    
    /**
     * Method to send text SMS 
     * Mahindra Portal Group
     * @param string $to - Mobile No
     * @param string $message - Test Content
     * return @param boolean
     */
    public static function sendTextSms($to, $message) 
    {
        try {
            $logData = array();
            $to = str_replace('+91', '', $to);
            $to = str_replace('+', '', $to);
            $logData['mobileNo'] = $to;
            
            $xmldata = '<?xml version="1.0" encoding="ISO-8859-1"?><!DOCTYPE MESSAGE SYSTEM "http://127.0.0.1/psms/dtd/messagev12.dtd" ><MESSAGE VER="1.2"><USER '.env('SMS_UNAME_KEY').'="'.env('SMS_UNAME_VAL').'" '.env('SMS_PSW_KEY').'="'. htmlentities(env('SMS_PSW_VAL')) .'"/><SMS UDH="0" CODING="1" TEXT="'.htmlentities($message, ENT_COMPAT).'" PROPERTY="0" ID="1"><ADDRESS FROM="clubmh" TO="'.$to.'" SEQ="1" TAG="ValueCallz Messages" /></SMS></MESSAGE>';
            
            $data = 'data=' . urlencode($xmldata);
            $action = 'action=send';
            $url = 'https://api.myvfirst.com/psms/servlet/psms.Eservice2';
            $postData = $action . '&' . $data;
            $objURL = curl_init($url);
            curl_setopt($objURL, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($objURL, CURLOPT_POST, 1);
            curl_setopt($objURL, CURLOPT_POSTFIELDS, $postData);
            $result = curl_exec($objURL);
            // $logData['response'] = $result;
            $status = curl_getinfo($objURL);
            
            $returnCode = (int) curl_getinfo($objURL, CURLINFO_HTTP_CODE);
            if ($returnCode == '404') {
                $result = 0;
            }
            curl_close($objURL);
            
            if ($result != '0') {
                $xml = simplexml_load_string($result);
                $json = json_encode($xml);
                $array = json_decode($json, true);
                //$smsStatus = count($array['GUID']['@attributes']);
                // $logData['status'] = $array;
                $smsStatus = true;
            } else {
                $smsStatus = false;
                // $logData['status'] = 'Failure';
            }
            return $smsStatus;
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * @return \Laravel\Lumen\Routing\UrlGenerator
     */
    function ownUrlGenerator() {
        return new \Laravel\Lumen\Routing\UrlGenerator(app());
    }
    
    /**
     * @param $path
     * @param bool $secured
     *
     * @return string
     */
    function ownAsset($path, $secured = false) {
        return $this->ownUrlGenerator()->asset($path, $secured);
    }

    //Send sms by twilio  
    public static function Send_SmS($mobile , $text){
          //Your Account SID and Auth Token from twilio.com/console
          $sid    = env( 'TWILIO_SID' );
          $token  = env( 'TWILIO_TOKEN' );
          $client = new Client( $sid, $token );
          try{  
                 $client->messages->create(
                 $mobile,
                 [
                     'from' => '12058830833',
                     'body' => $text,
                 ]
              );
              
              return 1;

          } catch (\Exception $e) {
            return 0; 
          }

    }

    /**
     * Method to get prio/after date of given date
     * $dateYMD: YYYY-MM-DD
     * $dayOffset: +/- day_count
     */
    public static function getPriorLaterDate($dateYMD, $dayOffset) {
        return date("Y-m-d", strtotime(date("Y-m-d", strtotime($dateYMD)) . " $dayOffset day"));
    }
    
    /**
     * Method to fetch RoomTypes
     */
    public static function roomTypes($room_category = ''){
        return \DB::table('roomtypemaster')
                ->select('roomtypeid as roomTypeID', 'roomtypename as roomType')
                ->where('portal_room_category', $room_category <> '' ? $room_category : 'superior')
                ->where('portal_room_sequence', '<>', 0)
                ->orderBy('portal_room_sequence', 'ASC')
                ->get()
                ->pluck('roomType', 'roomTypeID');
    }

    /**
     * Method to get all date between date range
     * $dateYMD: YYYY-MM-DD
     */
    public static function getAllDateBetweenDateRange($date1, $date2) {
        $newDates = [];
        if($date1 <> $date2) {
            $period = CarbonPeriod::create($date1, $date2);
            
            // Convert the period to an array of dates
            $dates = $period->toArray();
            foreach($dates as $date) {
                $newDates[] = $date->format('Y-m-d');
            }
            array_pop($newDates);
            return $newDates;
        } else {
            $period = CarbonPeriod::create($date1, $date2);

            // Convert the period to an array of dates
            
            $dates = $period->toArray();
            foreach($dates as $date) {
                $newDates[] = $date->format('Y-m-d');
            }
            return $newDates;
        }
    }

    /**
     * Method to get M/Y/D separately of given date
     * $dateYMD: YYYY-MM-DD
     */
    public static function getYMDSeparately($dateYMD = '', $flag = 'd') {
        $dateYMD = $dateYMD == '' ? date('Y-m-d') : $dateYMD;
        
        switch($flag){
            case 'y':
                $res = date('Y', strtotime($dateYMD));
                break;
            case 'm':
                $res = date('m', strtotime($dateYMD));
                break;
            case 'd':
                $res = date('d', strtotime($dateYMD));
                break;
            default:
            $res = date('d', strtotime($dateYMD));
        }
        return $res;
    }

    /**
     * Function for calling curl API 
     * @param string $method Api method name
     * @param array $params Api post parameters
     * @return array $response
     * Created by Teena khandelwal
     */
    public static function call_curl_api($url = null, $data = array(), $headers = array(), $method = 'post') {
        try {
            $not_log_method = array();
            $res = array();
            $log_data['UniqueID'] = $url;
            $log_data['Request'] = json_encode($data);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            if (!empty($headers)) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            }
            if ($method == 'post') {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
            $response = curl_exec($ch);
            $log_data['Response'] = $response;
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $log_data['statusCode'] = $status_code;
            $log_data['ServiceTimeTaken'] = $status_code['total_time'] . 'seconds'; 
            curl_close($ch);
            
            // Update Log 
            if (!in_array($url, $not_log_method)) {
                Log::Debug($log_data);
            }

            $res = array($response,$status_code);
            return $res;
        } catch (Exception $e) {
            $log_data['UniqueID']   = "Error" . ":" . $url;
            $log_data['Request']    = json_encode($data);
            $log_data['statusCode'] = $status_code;
            $log_data['Response'] = $e->getMessage();
            Log::Debug($log_data);
            return false;
        }
    }

    /**************************************
     * Redis Generic Helper Methods
     *************************************/
    
     /**
     * Function for get and set API data in memcache
     * @param string $method Api method name
     * @param string $key cache key for storing data
     * @param string $url Api url
     * @param array $headers for authorization token
     * Created by Teena khandelwal
     */
    public static function getRedisHashValByKey($list = '', $key = '') {
        try {
            if(empty($list) && empty($key)) {
                $response = ['status' => false, 'message' => 'Both Params Cant be empty.', 'result' => [] ];
            } elseif(empty($list)) {
                $response = ['status' => false, 'message' => 'first param(list) can not be empty.', 'result' => [] ];
            } elseif(empty($key) || $key == '*') {
                // return all keys from list
                $result = Redis::hgetall("{$list}");
                $nResult = [];
                foreach($result as $resultKey => $resultRow) { $nResult[$resultKey] = json_decode($resultRow, TRUE); }
                $response = ['status' => count($nResult) ? true : false, 'message' => count($nResult) ? 'data fetched successfully.' : 'data not found.', 'result' => $nResult ];
            } else {
                if(is_array($key)) {
                    $result = [];
                    foreach($key as $keyname){ $result["{$keyname}"] = json_decode(Redis::hget("{$list}", "{$keyname}"), TRUE); }
                    $response = ['status' => count($result) ? true : false, 'message' => count($result) ? 'data fetched successfully.' : 'data not found.', 'result' => $result ];
                } else {
                    // dd(Redis::hexists("{$list}", "{$key}"));
                    $result = Redis::hget("{$list}", "{$key}");
                    $response = ['status' => empty($result) ? false : true, 'message' => empty($result) ? 'data not found.' : 'data fetched successfully.', 'result' => json_decode($result,TRUE) ];
                }                
            }
            
            Log::info(['call_for' => 'Common@getRedisKeys', 'list' => $list, 'key' => $key, 'response' => $response]);
            return $response; 
        } catch (Exception $e) {
            Log::debug(['error_for' => 'Common@getRedisKeys', 'error' => json_encode($e->getMessage())]);
            $response = ['status' => false, 'message' => 'issue in method calling', 'error' => $e->getMessage()];
            return $response; 
        }
    }

    /**
     * Method to get first and last name 
     */
    public static function getSplitNames($name = '', $fallOn = false){
        $res = ['firstname' => '', 'lastname' => ''];
        if(trim($name) <> ''){
            $name = trim($name);
            $nameArr1 = $nameArr2 = explode(' ', $name);
            
            if($fallOn) {
                if(count($nameArr1) > 0 ) {
                    array_pop($nameArr1);
                    if(count($nameArr1) > 1) {
                        $res = ['firstname' => implode(' ', $nameArr1), 'lastname' => end($nameArr2)];
                        // $res = ['firstname' => $nameArr2[0], 'lastname' => $nameArr2[1]];
                    } else {
                        $res = ['firstname' => $nameArr2[0], 'lastname' => $nameArr2[1]];
                    }
                }
            } else {
                if(count($nameArr1) > 0 ) {
                    array_shift($nameArr1);
                    if(count($nameArr1) > 1) {
                        $res = ['firstname' => $nameArr2[0], 'lastname' => implode(' ', $nameArr1)];
                        // $res = ['firstname' => $nameArr2[0], 'lastname' => $nameArr2[1]];
                    } else {
                        $res = ['firstname' => $nameArr2[0], 'lastname' => $nameArr2[1]];
                    }
                }
            }
            
                
        }
        return $res;
    }
        
}

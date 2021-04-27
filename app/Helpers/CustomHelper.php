<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Models\TalismaLog;

class CustomHelper {

    public static function insertLog($method, $type, $data) {
        Log::info('' . $method . ': ' . $type . ' => ', array($data));
    }

    public static function sendSMS($mobileNumber, $msg) {
        try {
            $to = $mobileNumber;
            $xmlstr = <<<XML
<?xml version="1.0" encoding="ISO-8859-1"?><!DOCTYPE MESSAGE SYSTEM "http://127.0.0.1/psms/dtd/messagev12.dtd" ><MESSAGE VER="1.2"><USER USERNAME="%s" PASSWORD="%s"/><SMS  UDH="0" CODING="1" TEXT="%s" PROPERTY="0" ID="1"><ADDRESS FROM="%s" TO="%s" SEQ="1" TAG="ValueCallz Messages" /></SMS></MESSAGE>
XML;
            $xmldata = sprintf($xmlstr, 'MHRIL_SALES', htmlentities('mhril@sales'), htmlentities($msg, ENT_COMPAT), 'clubmh', $to, '1');
            $data = 'action=send&data=' . urlencode($xmldata);
            $action = 'action=send';

            $ch = curl_init();
            $url = Config::get('constants.SEND_SMS_API');
            $postfield = "action=send&data=" . $data;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            // In real life you should use something like:
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            // Receive server response ...
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $server_output = curl_exec($ch);
            curl_close($ch);
            return true;
        } catch (\Exception $ex) {
            print_r($ex->getMessage());
            exit;
            return FALSE;
        }
    }

    public static function curlCall($data, $url, $method) {
        try {            
            $ch = curl_init($url . $method);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
            );
            if (curl_errno($ch)) {
                curl_close($ch);
                return $response = FALSE;
            } else {
                $result = curl_exec($ch);
                curl_close($ch);
                $response = json_decode($result);
                return $response;
            }
            return $response;
        } catch (\Exception $e) {
            $log_data['Error'] = $e->getMessage();
            \Log::Debug($log_data);
        }
    }

    public static function elasticSearchCurlCall($data, $url, $port) {
        try {
            $log_data['URL'] = $url;
            $log_data['Port'] = $port;
            $log_data['Request'] = $data;
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_PORT => $port,
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "content-type: application/json"
                ),
            ));
            $result = curl_exec($curl);
            $log_data['Response'] = $result;

            // Update Log 
            if (empty($url) && empty($port)) {
                \Log::Debug($log_data);
            }

            $err = curl_error($curl);
            curl_close($curl);
            $response = json_decode($result, true);
            return $response;
        } catch (\Exception $e) {
            $log_data['Error'] = $e->getMessage();
            \Log::Debug($log_data);
        }
    }

    public static function getResult($result, $request) {
        if (!empty($result)) {
            $response = ['status' => true, 'message' => 'success', 'result' => $result];
        } else {
            $response = ['status' => false, 'message' => 'record not found.', 'result' => null];
        }
        Common::sendResponse($response, config('HttpCodes.code.success'), $request);
    }
    
    public static function InsertRedisData($result, $request) {
        if (!empty($result)) {
            $response = ['status' => true, 'message' => 'record inserted successfully'];
        } else {
            $response = ['status' => false, 'message' => 'record not found.'];
        }
        Common::sendResponse($response, config('HttpCodes.code.success'), $request);
    }

    public static function getLogData($method, $e) {
        Log::debug(['error_for' => $method, 'message' => $e->getMessage(), 'line no' => $e->getLine(), 'error' => json_encode($e, TRUE)]);
        return response()->json(['error' => config('HttpCodes.status.something_wrong')], config('HttpCodes.code.something_wrong'));
    }

    public static function talismaLog($api, $api_name, $userId, $contactId = null, $contractId = null, $reqParam, $response) {
        try {
            $talismaLogObj = new TalismaLog();
            $talismaLogObj->user_id = $userId;
            $talismaLogObj->api_name = $api_name;
            $talismaLogObj->api = $api;
            $talismaLogObj->contact_id = $contactId;
            $talismaLogObj->contract_id = $contractId;
            $talismaLogObj->request = json_encode($reqParam);
            $talismaLogObj->response = json_encode($response);
            $talismaLogObj->save();
            return true;
        } catch (\Exception $ex) {
            return TRUE;
        }
    }

    public static function splitNames($name) {
        $namesArray = [];
        $namesArray['first_name'] = '';
        $namesArray['last_name'] = '';
        if(!empty($name)) {
            $nameObj = explode(' ', $name);
            if(count($nameObj) > 1) {
                $namesArray['first_name'] = $nameObj[0];
                array_shift($nameObj);
                $namesArray['last_name'] = implode(' ', $nameObj);
            } else {
                $namesArray['first_name'] = $nameObj[0];
            }
        }
        return $namesArray;
    }

}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Helpers;

// Mail 
use Illuminate\Support\Facades\Mail;
use App\Mail\FeatureMail;

class MailHelper {

    /**
     * This function is used to send email
     * 
     * @param string $fileName
     * @param array $fileData
     * @param string $to
     * @param string $subject
     */
    public static function emailSend($fileName, $fileData, $to, $subject, $pathToFile = '') {
        // try {

            $details['fileName']    = $fileName;
            $details['fileData']    = $fileData ;
            $details['to']          = $to ;
            $details['subject']     = $subject;
            $details['pathToFile']  = $pathToFile;
            Mail::send(new FeatureMail($details));

            // Mail::send($fileName, $fileData, function($message) use ($to, $subject, $pathToFile) {
            //     if (!empty($pathToFile)) {
            //         $message->to($to)->subject($subject)->attach($pathToFile);
            //     } else {
            //         $message->to($to)->subject($subject);
            //     }
            // });
        // } catch (\Exception $ex) {
        //     print_r($ex->getMessage());exit;
        // }
    }

    public static function emailSendEmaf($fileName, $fileData, $to, $subject, $cc, $from) {
        \Mail::send($fileName, $fileData, function($message) use ($to, $subject, $cc, $from) {

            $message->to($to)
                    ->from($from)
                    ->cc($cc)
                    ->subject($subject);
        });
    }

}

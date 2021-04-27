<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
return [
    'code' => [
        'success' => 200, //Application key which generate api_token
        'fail' => 201,
        'bad_request' => 400,
        'unauthorised' => 401,
        'forbidden' => 403,
        'not_found' => 404,
        'parameters_missing' => 418,
        'something_wrong' => 500
    ],        
    'status' => [
        'success' => 'Success', //Application key which generate api_token
        'fail' => 'Fail',
        'bad_request' => 'Bad Request',
        'unauthorised' => 'Unauthorised',
        'forbidden' => 'Forbidden',
        'not_found' => 'Not Found',
        'parameters_missing' => 'Some Parameters are missing',
        'something_wrong' => 'Something went wrong, please try again'
    ],     
       
    'msg' => [
        'invalid_credentials' => 'invalid credentials', 
        'account_not_approve' => 'Account is not approved. Please contact on support.', 
        'account_inactive' => 'Account is not activated. Please contact on support.',  
        'records_fetched' => 'Records fetched successfully.',  
        'no_record' => 'No record found.',  
        'notification_created' => 'New notification is created successfully.',  
        'notification_read' => 'Notification is read successfully.',  
        'no_room_availble' => 'There is no room for booking',  
        'less_room_availble' => 'No. of required rooms are not available.',  
    ],     

    'otp_msg' => [
        'sucess' => 'OTP sent Successfully', 
        'not_sent' => 'OTP did not send',
        'mobile_not_exist' => 'Mobile number does not exist', 
        'otp_matched' => 'OTP matched Successfully',  
        'otp_not_matched' => 'OTP did not match',  
    ], 

    'forget_pass_msg' => [
        'sucess' => 'Password Changed Successfully', 
        'pwd_not_change' => 'Password did not Change',
        'account_blocked' => 'Account is blocked for 15 minute',
    ],

     'reset_pass_msg' => [
        'sucess' => 'Password has been updated successfully', 
        'pwd_not_match' => 'Please provide valid inputs!',
    ], 

    'mail' => [
        'booking_subject' => 'Successfull Booking (ID - :bookingId'.') | '.env('MAIL_FROM_NAME', 'Prodis B2B'),
        'enquiry_subject' => 'Successfull Enquiry (ID - :ennquiryId'.') | '.env('MAIL_FROM_NAME', 'Prodis B2B'),
    ], 

    'feed_back_msg' => [
        'sucess'        => 'Feedback Submitted Successfully', 
        'not_found_authorise'  => 'Booking is not found or you are not authorised.', 
        'error'  => 'Found technical issue, please try later.', 
    ], 

   'booking_msg' => [
        'cancel_booking' => 'Cancelled Successfully', 
        'not_cancel_booking' => 'Booking/enqury can not cancel from panel, please contact admin', 
        'booking_not_exist' => 'Booking/enquiry does not exist', 
    ], 

];
 
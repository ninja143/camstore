<?php

defined("APPROVED") or define("APPROVED", "1");
defined("ACTIVE") or define("ACTIVE", "1");
defined("ZERO") or define("ZERO", 0);
defined("PRODUCT_ACTIVE") or define("PRODUCT_ACTIVE", 'Active');
defined("PRODUCT_INACTIVE") or define("PRODUCT_INACTIVE", 'Inactive');
return[
    'SERVER_TALISMA_URL' => env('SERVER_TALISMA_URL'),
    'SERVER_TALISMA_DB' => env('SERVER_TALISMA_DB'),
    'SERVER_TALISMA_ID' => env('SERVER_TALISMA_ID'),
    'SERVER_TALISMA_PWD' => env('SERVER_TALISMA_PWD'),
    'SEND_SMS_API' => env('SEND_SMS_API'),
    'CLICKTOBUY_JOYSCAPE_URL' => env('CLICKTOBUY_JOYSCAPE_URL'),
    'CLICKTOBUY_UPDATE_MEMBER_DETAILS' => env('CLICKTOBUY_UPDATE_MEMBER_DETAILS'),
    'CLICKTOBUY_CREATE_CUSTOMER' => env('CLICKTOBUY_CREATE_CUSTOMER'),
    'CLICKTOBUY_CONTRACT_CREATION' => env('CLICKTOBUY_CONTRACT_CREATION'),
    'CLICKTOBUY_UPDATE_EKYC_DETAILS' => env('CLICKTOBUY_UPDATE_EKYC_DETAILS'),
    'INSERT_CONTRACT_DP_DETAILS' => env('INSERT_CONTRACT_DP_DETAILS'),
    'fb_app_id' => env('fb_app_id'),
    'fb_app_secret' => env('fb_app_secret'),
    'fb_default_graph_version' => env('fb_default_graph_version'),
    'fb_access_token' => env('fb_access_token'),
    'insta_user' => env('insta_user'),
    'ELASTIC_SEARCH_API_URL' =>  env('ELASTIC_SEARCH_API_URL'), 
    'ELASTIC_SEARCH_API_PORT' => env('ELASTIC_SEARCH_API_PORT'), 
    'PUSH_SAP_DATA' => env('PUSH_SAP_DATA'),
    'CENTRALIZED_PAYMENT_API_URL' => env('CENTRALIZED_PAYMENT_API_URL'),
    'RAND_MIN_ORDERID' => env('RAND_MIN_ORDERID'),
    'RAND_MAX_ORDERID' => env('RAND_MAX_ORDERID'),
];

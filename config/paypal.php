<?php
return array(
    /** set your paypal credential **/
//    'client_id' =>'AT4Xp1jh8-bJc5_-kBlVsFoCQEUSXLNoW-EOejtzDHWSsRIWV-VuRpGo5Xy18PahVj1__xxYEO-dyMhz',
//    'secret' => 'EBLtHYGM18mjcWLKvBN-_ML49IM7zkBDKoGxQVsavuBOe5zxADwdeMa0UA8rF3sMzLZANrVJ9hvFdNqu',
    'client_id' =>'AavFy1PBhCzLC27KJLCgi9054wswB-AMJebBvPH3md5jR9AhI87XZYAKX-YedFhQikfPGuve4ApjJMCp',
    'secret' => 'EMX5H8QrCHibfXLrzjK4h2n3EC7294qpEQYKrYc8iFdPNp8almQM52tG-3qEzyt8L4HjBe7up3ZanMHT',
    /**
     * SDK configuration
     */
    'settings' => array(
        /**
         * Available option 'sandbox' or 'live'
         */
        'mode' => 'sandbox',
        /**
         * Specify the max request time in seconds
         */
        'http.ConnectionTimeOut' => 1000,
        /**
         * Whether want to log to a file
         */
        'log.LogEnabled' => true,
        /**
         * Specify the file that want to write on
         */
        'log.FileName' => storage_path() . '/logs/paypal.log',
        /**
         * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
         *
         * Logging is most verbose in the 'FINE' level and decreases as you
         * proceed towards ERROR
         */
        'log.LogLevel' => 'FINE'
    ),
);
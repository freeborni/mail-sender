<?php
require('vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if(isset($_POST) && !empty($_POST)){

    $fullname = $_POST['fullname'];
    $message = $_POST['message'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
        
    $data = [
        'personalizations' => [
            [
                'to' => [["email"=> "freeborniwarr@gmail.com"]],
                'dynamic_template_data' => [
                    'sender'  => "$fullname",
                    'message' => "$message"
                ]
            ]
        ],
        'from'=> ["email"=> "freeborn.iwarri@dreamlabs.com.ng"],
        'subject' => "$subject",
        'template_id' => 'd-af438611510849048b3467fe2cab124c'
    ];
    
    $ch = curl_init('https://api.sendgrid.com/v3/mail/send');

    curl_setopt_array($ch,[
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer '.$_ENV['SENDGRID_API_KEY']
        ],
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_RETURNTRANSFER => true
    ]);

    $res = curl_exec($ch);
    $res = json_decode($res);

    
    if($res->errors){
        header("HTTP/1.1 400 Bad Request");
        $res_arr = [
            'status'=>'error',
            'message'=> $res->errors
        ];
    }else{
    
        header('Content-Type: application/json');
        $res_arr = [
            'status'=>'success',
            'message'=>'email sent successfully'
        ];
    }

    echo json_encode($res_arr);

    curl_close($ch);

}
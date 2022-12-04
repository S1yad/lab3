<?php


$data = json_decode(file_get_contents('php://input'), TRUE);
file_put_contents('file.txt', '$data: '.print_r($data, 1)."\n", FILE_APPEND);

//https://api.telegram.org/bot5941915204:AAE_H3h19XzNpKZ4reSAS4QwDVRFJ_RhIFU/setwebhook?url=https://github.com/S1yad/lab3.git
$data = $data['callback_query'] ? $data['callback_query'] : $data['message'];

$token = '5941915204:AAE_H3h19XzNpKZ4reSAS4QwDVRFJ_RhIFU';
define('TOKEN', '5941915204:AAE_H3h19XzNpKZ4reSAS4QwDVRFJ_RhIFU');

$message = $data['message']['text'];
$message = mb_strtolower(($data['text'] ? $data['text'] : $data['data']),'utf-8');

switch ($message)
{
    case 'текст':
        $method = 'sendMessage';
        $send_data = [
            'text'   => 'Моя відповідь'
        ];
        break;

    case 'кнопки':
        $method = 'sendMessage';
        $send_data = [
            'text'   => 'Мої кнопки',
            'reply_markup' => [
                'resize_keyboard' => true,
                'keyboard' => [
                    [
                        ['text' => 'Відео'],
                        ['text' => 'Кнопка 2'],
                    ],
                    [
                        ['text' => 'Кнопка 3'],
                        ['text' => 'Кнопка 4'],
                    ]
                ]
            ]
        ];
        break;


    case 'видео':
        $method = 'sendVideo';
        $send_data = [
            'video'   => 'https://',
            'caption' => 'Моє відео:',
            'reply_markup' => [
                'resize_keyboard' => true,
                'keyboard' => [
                    [
                        ['text' => 'Кнопка 1'],
                        ['text' => 'Кнопка 2'],
                    ],
                    [
                        ['text' => 'Кнопка 3'],
                        ['text' => 'Кнопка 4'],
                    ]
                ]
            ]
        ];
        break;

    default:
        $method = 'sendMessage';
        $send_data = [
            'text' => 'Не розумію про що ви :('
        ];
}
$send_data['chat_id'] = $data['chat']['id'];

$res = sendTelegram($method, $send_data);

function sendTelegram($method, $data, $headers = [])
{
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://api.telegram.org/bot' . TOKEN . '/' . $method,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array_merge(array("Content-Type: application/json"), $headers)
    ]);   
    
    $result = curl_exec($curl);
    curl_close($curl);
    return (json_decode($result, 1) ? json_decode($result, 1) : $result);
}
$params = [
    'chat_id' => $data['message']['chat']['id'],
    'text'    => $message
];


file_get_contents('https://api.telegram.org/bot'.$token.'/sendMessage?'.http_build_query($params));
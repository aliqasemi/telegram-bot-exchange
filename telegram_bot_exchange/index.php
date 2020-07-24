<?php
//create web Socket
$update = file_get_contents('php://input') ;

file_put_contents('bot.txt' , $update) ;
$update = json_decode($update , TRUE) ;
$message = $update['message']['text'] ;
$chatID = $update['message']['from']['id'] ;
function sendMessage($chatID , $text){
    $url = 'https://api.telegram.org/bot939115526:AAEou5lXkuXwfkOWvVVLr2apM7pBX4pN9dw/sendMessage?chat_id='.$chatID."&text=".$text ;
    file_get_contents($url) ;
}
function sendPhoto($chatID , $photo){
    $url = 'https://api.telegram.org/bot939115526:AAEou5lXkuXwfkOWvVVLr2apM7pBX4pN9dw/sendPhoto?chat_id='.$chatID."&photo=".$photo ;
    file_get_contents($url) ;
}
if ($message == '/start'){
    sendMessage($chatID , 'سلام به ربات گزارشات بورسی خوش آمدید.');
    sendPhoto($chatID , 'https://www.alitajran.com/wp-content/uploads/2020/01/Exchange-logo.png');
}


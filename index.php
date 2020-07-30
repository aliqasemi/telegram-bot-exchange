<?php
//create web Socket
$update = file_get_contents('php://input') ;

file_put_contents('bot.txt' , $update) ;
$update = json_decode($update , TRUE) ;

//detect value for call back query or send message
if (isset($update['callback_query'])){
    $data = $update['callback_query']['data'];
    $msgid = $update['callback_query']['message']['message_id'];
    $chatID = $update['callback_query']['from']['id'];


}
else{
    $message= $update['message']['text'];
    $chatID = $update['message']['from']['id'];
}

//function for send message
function sendMessage($chatID , $text){
    $url = 'https://api.telegram.org/bot995623838:AAH4-6nLgfn91-9dA3Ba2H7FgHgnsGS1l6g/sendMessage?chat_id='.$chatID."&text=".$text ;
    file_get_contents($url) ;
}

//function for send message with key button
function sendMessageKey($chatID , $text , $key){
    $url = 'https://api.telegram.org/bot995623838:AAH4-6nLgfn91-9dA3Ba2H7FgHgnsGS1l6g/sendMessage?chat_id='.$chatID."&text=".$text.'&reply_markup='.$key ;
    file_get_contents($url) ;
}

//function for send photo
function sendPhoto($chatID , $photo){
    $url = 'https://api.telegram.org/bot995623838:AAH4-6nLgfn91-9dA3Ba2H7FgHgnsGS1l6g/sendPhoto?chat_id='.$chatID."&photo=".$photo ;
    file_get_contents($url) ;
}

//function for edit massage
function edit($chatid,$msgid,$text,$key){
    $url = 'https://api.telegram.org/bot995623838:AAH4-6nLgfn91-9dA3Ba2H7FgHgnsGS1l6g/editMessageText?chat_id='.$chatid."&message_id=".$msgid.'&text='.$text.'&reply_markup='.$key;
    file_get_contents($url);
}

//function for delete massage
function delete($chatid,$msgid){
    $url = 'https://api.telegram.org/bot995623838:AAH4-6nLgfn91-9dA3Ba2H7FgHgnsGS1l6g/deleteMessage?chat_id='.$chatid."&message_id=".$msgid;
    file_get_contents($url);
}

//function for create button
function keyboard($text){
    $btn = array(
        array(
            $text
        )
    );
    $key = array(
        'keyboard' => $btn  ,
        'resize_keyboard' => true ,
        'one_time_keyboard' => false ,
        'selective' => true
    );
    $final = json_encode($key , true) ;
    return $final ;
}

//create inline keyboard

function InlineKeyboardButton($text,$data){
    $opt= [
        'text'=>$text,
        'callback_data'=>$data
    ];
    return $opt;
}
function InlineKeyboardMarkup(array $opt){
    $reply = [
        'inline_keyboard' => $opt
    ];
    $final_reply = json_encode($reply,TRUE);
    return $final_reply;
}



if ($message == '/start'){
    sendMessage($chatID , 'سلام به ربات گزارشات بورسی خوش آمدید');
    sendMessage($chatID , 'از طرف تیم edu_exchange');
    sendPhoto($chatID , 'https://www.alitajran.com/wp-content/uploads/2020/01/Exchange-logo.png');
    $key_button = keyboard('قیمت دلار') ;
    sendMessageKey($chatID , 'انتخاب کنید' , $key_button);

}


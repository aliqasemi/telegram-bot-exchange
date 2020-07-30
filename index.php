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

//function for join channel
function join_channel(){
    return "https://t.me/edu_exchange" ;
}

//file check user is a channel member or not

function check_Member($chat_id,$channel_id){
    $url = "https://api.telegram.org/bot995623838:AAH4-6nLgfn91-9dA3Ba2H7FgHgnsGS1l6g/getChatMember?user_id=".$chat_id.'&chat_id='.$channel_id;
    $result=  file_get_contents($url);
    $result_json = json_decode( $result ,TRUE);
    $status = $result_json['result']['status'];
    if ($status=='left'){
        return false;

    }
    else{
        return true;
    }
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
        'callback_data'=>$data ,
    ];
    return $opt;
}

//create inline keyboard with url

function InlineKeyboardButtonWithUrl($text,$data, $url){
    $opt= [
        'text'=>$text,
        'callback_data'=>$data ,
        'url' =>  $url
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

//button for check member
$channel = array(
    array(
        InlineKeyboardButtonWithUrl('عضویت در کانال edu_exchange','channel' , 'https://t.me/edu_exchange')
    )
) ;


//button for get information
$data_select = array(
    array(
        InlineKeyboardButton('قیمت دلار','دلار'),  InlineKeyboardButton('قیمت یورو','یورو')
    ) ,
    array(
        InlineKeyboardButton(' شاخص کل بورس','بورس'),  InlineKeyboardButton('شاخص کل فرابورس','فرابورس')
    )
);

//set markup button
$channel_select = InlineKeyboardMarkup($channel) ;
$button_select = InlineKeyboardMarkup($data_select);



if ($message == '/start'){
    $check_member = check_Member($chatID , '@edu_exchange') ;
    if ($check_member){
        sendMessage($chatID , 'سلام به ربات گزارشات بورسی خوش آمدید');
        sendMessage($chatID , 'از طرف تیم edu_exchange');
        sendPhoto($chatID , 'https://www.alitajran.com/wp-content/uploads/2020/01/Exchange-logo.png');
        $key_button1 = keyboard('قیمت دلار') ;

        sendMessageKey($chatID , '' , $key_button1);
        sendMessageKey($chatID , 'انتخاب کنید' , $button_select);


        if (isset($data)){
            if ($data=="دلار"){
                $price = arz('دلار');
                $text1 = 'قیمت دلار هم اکنون برابر '.$price.'ریال میباشد';
                edit($chatID,$msgid,$text1,$button_select);
            }
            elseif ($data=="یورو"){
                $price = arz('یورو');
                $text1 = 'قیمت یورو هم اکنون برابر '.$price.'ریال میباشد';
                edit($chatID,$msgid,$text1,$button_select);
            }
        }
    }
    else{

        sendMessage($chatID , 'کاربر گرامی برای استفاده از ربات ابتدا وارد کانال edu_exchange شوید.');
        sendMessageKey($chatID , 'با دکمه زیر عضو کانال عضو شوید' , $channel_select);
        exit();
    }


}


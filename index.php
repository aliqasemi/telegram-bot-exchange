<?php
//create web Socket
$update = file_get_contents('php://input') ;

file_put_contents('bot.txt' , $update) ;
$update = json_decode($update , TRUE) ;


$servername = "aliqasemi1377.ir";
$username = "aliqasem_edu_exchange";
$password = ".]v}Nm{RLW3g";
$dbname = "aliqasem_edu_exchange";

// Create connection
$connect = new mysqli($servername, $username, $password , $dbname);

// Check connection
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}
echo "Connected successfully";



//detect value for call back query or send message
if (isset($update['callback_query'])){
    $data = $update['callback_query']['data'];
    $msgid = $update['callback_query']['message']['message_id'];
    $chatID = $update['callback_query']['from']['id'];
}
else{
    $message= $update['message']['text'];
    $chatID = $update['message']['from']['id'];
    $is_bot = $update['message']['from']['is_bot'] ;
    $first_name = $update['message']['from']['first_name'] ;
    $last_name = $update['message']['from']['last_name'] ;
    $username = $update['message']['from']['username'] ;
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

//function for get value with api
function value($fild){
    $currency =json_decode( file_get_contents('https://www.megaweb.ir/api/money'),TRUE);

    if ($fild=='دلار'){
        return  $currency['buy_usd']['price'];
    }
    elseif($fild=='یورو'){
        return  $currency['buy_eur']['price'];
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


//function for add values table
function addValuesTable($connect){




    $ch1 = curl_init("https://oneapi.ir/api/bourse");

    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch1, CURLOPT_HTTPHEADER, array("OneAPI-Key: 8f39eb06bcc02f97b854e5e5907b809f"));

    $response1 = curl_exec($ch1);

    $ch2 = curl_init("https://oneapi.ir/api/bourse/overseas");

    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch2, CURLOPT_HTTPHEADER, array("OneAPI-Key: 8f39eb06bcc02f97b854e5e5907b809f"));

    $response2 = curl_exec($ch2);

    curl_close($ch2);

    $bourse1 = json_decode($response1 , TRUE) ;

    $bourse2 = json_decode($response2 , TRUE) ;

    if ($bourse1[0]["status"] == -3 || $bourse2[0]["status"] == -3){
        echo "error" ;
    }
    else{
        foreach ($bourse1 as $b){
            $symbol = $b["symbol"] ;
            $name = $b["name"] ;
            $market = $b["market"] ;
            $flow = $b["flow"] ;
            $last_price_value = $b["last_price"]["value"] ;
            $last_price_change = $b["last_price"]["change"] ;
            $last_price_percent = $b["last_price"]["percent"] ;
            $last_price_status = $b["last_price"]["status"] ;
            $final_price_value = $b["final_price"]["value"] ;
            $final_price_change = $b["final_price"]["change"] ;
            $final_price_percent = $b["final_price"]["percent"] ;
            $final_price_status = $b["final_price"]["status"] ;
            $trades_date = $b["trades"]["date"] ;
            $trades_count = $b["trades"]["count"] ;
            $trades_volume = $b["trades"]["volume"] ;
            $trades_value = $b["trades"]["value"] ;
            $trades_medium = $b["trades"]["medium"] ;
            $prices_yesterday = $b["prices"]["yesterday"];
            $prices_first = $b["prices"]["first"];
            $prices_low = $b["prices"]["low"];
            $prices_high = $b["prices"]["high"];
            $buy_count = $b["buy"]["count"] ;
            $buy_volume = $b["buy"]["volume"] ;
            $buy_price = $b["buy"]["price"] ;
            $sale_count = $b["sale"]["count"] ;
            $sale_volume = $b["sale"]["volume"] ;
            $sale_price = $b["sale"]["price"] ;
            $market_value = $b["market_value"] ;
            $property_today = $b["property_today"] ;
            $property_realty = $b["property_realty"] ;
            $last_capital = $b["last_capital"] ;
            $debt = $b["debt"] ;
            $salary = $b["salary"] ;
            $income = $b["income"] ;
            $ttm = $b["ttm"] ;
            $pe = $b["pe"] ;
            $pb = $b["pb"] ;
            $ps = $b["ps"] ;
            $buy_vol_person = $b["buy_vol"]["person"] ;
            $buy_vol_legal = $b["buy_vol"]["legal"] ;
            $sale_vol_person = $b["sale_vol"]["person"] ;
            $sale_vol_legal = $b["sale_vol"]["legal"] ;
            $type = "نوع" ;
            $sql = "INSERT INTO `values` 
            (`symbol`, `name`, `market`, `flow`, 
            `last_price.value`, `last_price.change`, 
            `last_price.percent`, `last_price.status`, 
            `final_price.value`, `final_price.change`, 
            `final_price.percent`, `final_price.status`, 
            `trades.date`, `trades.count`, `trades.volume`, 
            `trades.value`, `trades.medium`, `prices.yesterday`, 
            `prices.first`, `prices.low`, `prices.high`, `buy.count`, 
            `buy.volume`, `buy.price`, `sale.count`, `sale.volume`, `sale.price`, 
            `market_value`, `property_today`, `property_realty`, `last_capital`, 
            `debt`, `salary`, `income`, `ttm`, `pe`, `pb`, `ps`, `buy_vol.person`, 
            `buy_vol.legal`, `sale_vol.person`, `sale_vol.legal`, `type`)
             VALUES ('$symbol', '$name', '$market', '$flow',
              '$last_price_value', '$last_price_change',
               '$last_price_percent', '$last_price_status',
                '$final_price_value', '$final_price_change',
                 '$final_price_percent', '$final_price_status',
                  '$trades_date', '$trades_count', '$trades_volume',
                   '$trades_value', '$trades_medium', '$prices_yesterday',
                    '$prices_first', '$prices_low', '$prices_high', '$buy_count',
                     '$buy_volume', '$buy_price', '$sale_count', '$sale_volume', '$sale_price',
                      '$market_value', '$property_today', '$property_realty ', '$last_capital',
                       '$debt', '$salary', '$income' ,  '$ttm', '$pe', '$pb', '$ps', '$buy_vol_person' ,
                         '$buy_vol_legal' , '$sale_vol_person', '$sale_vol_legal', '$type' )
" ;
            if ($connect->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $connect->error;
            }
        }


        foreach ($bourse2 as $b){
            $symbol = $b["symbol"] ;
            $name = $b["name"] ;
            $market = $b["market"] ;
            $flow = $b["flow"] ;
            $last_price_value = $b["last_price"]["value"] ;
            $last_price_change = $b["last_price"]["change"] ;
            $last_price_percent = $b["last_price"]["percent"] ;
            $last_price_status = $b["last_price"]["status"] ;
            $final_price_value = $b["final_price"]["value"] ;
            $final_price_change = $b["final_price"]["change"] ;
            $final_price_percent = $b["final_price"]["percent"] ;
            $final_price_status = $b["final_price"]["status"] ;
            $trades_date = $b["trades"]["date"] ;
            $trades_count = $b["trades"]["count"] ;
            $trades_volume = $b["trades"]["volume"] ;
            $trades_value = $b["trades"]["value"] ;
            $trades_medium = $b["trades"]["medium"] ;
            $prices_yesterday = $b["prices"]["yesterday"];
            $prices_first = $b["prices"]["first"];
            $prices_low = $b["prices"]["low"];
            $prices_high = $b["prices"]["high"];
            $buy_count = $b["buy"]["count"] ;
            $buy_volume = $b["buy"]["volume"] ;
            $buy_price = $b["buy"]["price"] ;
            $sale_count = $b["sale"]["count"] ;
            $sale_volume = $b["sale"]["volume"] ;
            $sale_price = $b["sale"]["price"] ;
            $market_value = $b["market_value"] ;
            $property_today = $b["property_today"] ;
            $property_realty = $b["property_realty"] ;
            $last_capital = $b["last_capital"] ;
            $debt = $b["debt"] ;
            $salary = $b["salary"] ;
            $income = $b["income"] ;
            $ttm = $b["ttm"] ;
            $pe = $b["pe"] ;
            $pb = $b["pb"] ;
            $ps = $b["ps"] ;
            $buy_vol_person = $b["buy_vol"]["person"] ;
            $buy_vol_legal = $b["buy_vol"]["legal"] ;
            $sale_vol_person = $b["sale_vol"]["person"] ;
            $sale_vol_legal = $b["sale_vol"]["legal"] ;
            $type = "نوع" ;
            $sql = "INSERT INTO `values` 
            (`symbol`, `name`, `market`, `flow`, 
            `last_price.value`, `last_price.change`, 
            `last_price.percent`, `last_price.status`, 
            `final_price.value`, `final_price.change`, 
            `final_price.percent`, `final_price.status`, 
            `trades.date`, `trades.count`, `trades.volume`, 
            `trades.value`, `trades.medium`, `prices.yesterday`, 
            `prices.first`, `prices.low`, `prices.high`, `buy.count`, 
            `buy.volume`, `buy.price`, `sale.count`, `sale.volume`, `sale.price`, 
            `market_value`, `property_today`, `property_realty`, `last_capital`, 
            `debt`, `salary`, `income`, `ttm`, `pe`, `pb`, `ps`, `buy_vol.person`, 
            `buy_vol.legal`, `sale_vol.person`, `sale_vol.legal`, `type`)
             VALUES ('$symbol', '$name', '$market', '$flow',
              '$last_price_value', '$last_price_change',
               '$last_price_percent', '$last_price_status',
                '$final_price_value', '$final_price_change',
                 '$final_price_percent', '$final_price_status',
                  '$trades_date', '$trades_count', '$trades_volume',
                   '$trades_value', '$trades_medium', '$prices_yesterday',
                    '$prices_first', '$prices_low', '$prices_high', '$buy_count',
                     '$buy_volume', '$buy_price', '$sale_count', '$sale_volume', '$sale_price',
                      '$market_value', '$property_today', '$property_realty ', '$last_capital',
                       '$debt', '$salary', '$income' ,  '$ttm', '$pe', '$pb', '$ps', '$buy_vol_person' ,
                         '$buy_vol_legal' , '$sale_vol_person', '$sale_vol_legal', '$type' )
" ;
            if ($connect->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $connect->error;
            }
        }





    }





//echo $update[0]["name"].'hello'
//var_dump($bourse);



}



//function for get user information and add to table
function addUser($connect , $chatID , $username , $first_name , $last_name){
    $sql = "select `chat_id` from `users` where `chat_id`= $chatID";

    $query = $connect->query($sql) ;

    $count = $query->num_rows ;

    if ($count == 0){
        $sql = "INSERT INTO `users`(`username`, `first_name`, `last_name`, `count`, `chat_id`)
                VALUES (
                '$username','$first_name',
                '$last_name',0,
                $chatID
                )" ;
        $connect->query($sql) ;
    }

    return $count ;
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



//addValuesTable($connect);


$check_member = check_Member($chatID , '@edu_exchange') ;
if($message == '/start' or isset($data)){
    if ($check_member){
        if ($message == '/start'){

            addUser($connect , $chatID , $username , $first_name , $last_name) ;
            sendMessage($chatID , 'سلام به ربات گزارشات بورسی خوش آمدید');
            sendMessage($chatID , 'از طرف تیم edu_exchange');
            sendPhoto($chatID , 'https://www.alitajran.com/wp-content/uploads/2020/01/Exchange-logo.png');
            //$key_button1 = keyboard('قیمت دلار') ;

            //sendMessageKey($chatID , '' , $key_button1);
            sendMessageKey($chatID,"انتخاب کنید",$button_select);
        }

        if (isset($data)){
            if ($data == "دلار" ){
                $price = value('دلار');
                $text1 = 'قیمت دلار هم اکنون برابر '.$price.' ریال میباشد';
                edit($chatID,$msgid,$text1,$button_select);
                exit();
            }
            elseif ( $data == "یورو" ){
                $price = value('یورو');
                $text1 = 'قیمت یورو هم اکنون برابر '.$price.' ریال میباشد';
                edit($chatID,$msgid,$text1,$button_select);
                exit();
            }
            elseif ($data == "بورس"){
                $price = 6;
                $text1 = 'شاخص کل بورس تهران هم اکنون برابر '.$price.' واحد میباشد';
                edit($chatID,$msgid,$text1,$button_select);
                exit();
            }
            elseif ($data == "فرابورس"){
                $price = 6;
                $text1 = 'شاخص کل فرا بورس هم اکنون برابر '.$price.' واحد میباشد';
                edit($chatID,$msgid,$text1,$button_select);
                exit();
            }
        }

        if(isset($message)){
            //
        }


    }
    else{
        sendMessage($chatID , 'کاربر گرامی برای استفاده از ربات ابتدا وارد کانال edu_exchange شوید.');
        sendMessageKey($chatID , 'با دکمه زیر عضو کانال عضو شوید' , $channel_select);
        exit();
    }


}




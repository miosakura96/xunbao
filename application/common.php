<?php
// 父级外部跳转
// url must 用url函数去生成
function parToURLExit($message = 'no message...',$url){
    echo "<script> alert('" . $message . "'); window.parent.location.href = '" . $url . "' </script>";
    die();
}

// 同级返回
function sonToReuExit($message = 'no message...'){
    echo "<script> alert('" . $message . "'); history.back(); </script>";
    die();
}


function urlToRed($message = 'no message...',$url){
    echo "<script> alert('" . $message . "'); window.location.href = '" . $url . "' </script>";
    die();
}



// getToken
function getToken()
{
    $TOKEN_URL="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".config('weixin')['appid']."&secret=".config('weixin')['appsec'];
    $data = json_decode(getCurl($TOKEN_URL));
    $token = $data->access_token;
    return $token;
}

function getCurl($url='')
{
    //初始化
    $curl = curl_init();
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, false);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //执行命令
    $data = curl_exec($curl);
    //关闭URL请求
    curl_close($curl);
    //显示获得的数据
    return $data;
}


function shuffleNumber($number, $min, $max){
    $val = rand($number - $min, $number + $max);
    return $val;
}

// 返回地址
function regStr(){
    $str = config('view_replace_str')['__IMG__'];
    return $str;
}

<?php

namespace app\index\controller;

use think\cache\driver\Redis;
use think\Controller;
use think\Request;

class JsApiController extends Controller
{
    public $appId;

    public $appsecret;

    public $timestamp;

    public $nocestr;

    public $signature;

    public $redis;


    public function __construct(Request $request = null)
    {
        parent::__construct($request);

        $this->appId = config('weixin')['appid'];
        $this->appsecret = config('weixin')['appsec'];
        $this->redis = new Redis();
        $this->setVal();
    }

    public function setVal()
    {
        $this->timestamp = $this->make_timestamp();
        $this->nocestr = $this->make_nonceStr();
        $this->signature = $this->make_signature();
    }

    public function make_timestamp()
    {
        $timestamp = time();
        return $timestamp;
    }

    public function make_nonceStr()
    {
        $codeSet = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        for ($i = 0; $i<16; $i++) {
            $codes[$i] = $codeSet[mt_rand(0, strlen($codeSet)-1)];
        }

        $nonceStr = implode($codes,'');
        return $nonceStr;
    }

    function make_signature()
    {
        $tmpArr = array(
            'noncestr' => $this->nocestr,
            'timestamp' => $this->timestamp,
            'jsapi_ticket' => $this->make_ticket(),
            'url' => "http://xunbaosc.com/think/iGoods.html"
        );
        ksort($tmpArr, SORT_STRING);
        $string1 = http_build_query( $tmpArr );
        $string1 = urldecode( $string1 );
        $signature = sha1( $string1 );
        return $signature;
    }

    function make_ticket()
    {
        $accessTokenData = $this->redis->get('access_token');

        if (empty($accessTokenData)) {

            $access_token = getToken();
            $this->redis->set('access_token',$access_token,7000);

        }else{
            $access_token = $accessTokenData;
        }

        $ticketData = $this->redis->get('ticket');

        if (empty($ticketData)) {
            $ticket_URL="https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$access_token."&type=jsapi";
            $json = getCurl($ticket_URL);
            $result = json_decode($json,true);
            $ticket = $result['ticket'];
            if ($ticket) {
                $this->redis->set('ticket', $ticket, 7000);
            }
        }else{
            $ticket = $ticketData;
        }


        return $ticket;
    }

    public function getVal()
    {
        $arr = [
            'signature'  =>  $this->signature,
            'timestamp'  =>  $this->timestamp,
            'nocestr'  =>  $this->nocestr,
            'appid'  =>  $this->appId,
        ];
        return json($arr);
    }
}

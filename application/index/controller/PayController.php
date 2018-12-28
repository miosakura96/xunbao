<?php

namespace app\index\controller;

use Doctrine\Common\Cache\RedisCache;
use Thenbsp\Wechat\Payment\Jsapi\PayChoose;
use Thenbsp\Wechat\Payment\Unifiedorder;
use Thenbsp\Wechat\Wechat\AccessToken;
use Thenbsp\Wechat\Wechat\Jsapi;
use think\Controller;
use think\Request;

class PayController extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);

        $cacheDriver = new \Doctrine\Common\Cache\RedisCache();
        $cacheDriver->setRedis($redis);

        $appid = config('weixin')['appid'];
        $appsec = config('weixin')['appsec'];
        $accessToken = new AccessToken($appid,$appsec);
        $accessToken->setCache($cacheDriver);

        $jsapi = new Jsapi($accessToken);
        $jsapi->setCache($cacheDriver);
        $jsapi->addApi('chooseWXPay');

        // 生成订单
        $unifiedorder = new Unifiedorder($appid, config('weixin')['mid'], config('weixin')['key']);
//        dump($unifiedorder);
        $unifiedorder->set('body',          'xiaolaodi');
        $unifiedorder->set('total_fee',     config('weixin')['price']);
        $unifiedorder->set('openid',        $this->nowUser->open_id);
        $unifiedorder->set('out_trade_no',  date('YmdHis').mt_rand(10000, 99999));
        $unifiedorder->set('notify_url',    'https://www.baidu.com/');

        $config = new PayChoose($unifiedorder);

        return $this->fetch('index', compact('config', 'jsapi'));
    }


}

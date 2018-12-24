<?php

namespace app\index\controller;

use app\common\model\Flower;
use app\common\model\Goods;
use app\common\model\Ips;
use app\common\model\Join;
use app\common\model\Message;
use app\common\model\Order;
use app\common\model\Types;
use app\common\model\UnUser;
use app\common\model\Users;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Thenbsp\Wechat\User\User;
use think\cache\driver\Redis;
use think\Controller;
use think\Db;
use think\Request;
use think\Route;

class GoodsController extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        // 用户必须手机验证
        if ($this->nowUser->is_sms == 0) urlToRed('必须验证手机号', url('/phoneCheck'));

        $types = Types::select();
        $this->assign('types', $types);
        return $this->fetch('index');
    }

    public function release(Request $request)
    {
        if ($request->isPost()) {

            // 实名认证
            if ($this->nowUser->is_auth != 2 && $this->nowUser->release_count > 5) {
                return json([
                    'state' => 'auth',
                    'msg' => '请先进行实名认证'
                ]);
            }

            // 认证中
            if ($this->nowUser->is_auth == 1) {
                return json([
                    'state' => 'auth_now',
                    'msg' => '您的资料已在认证中'
                ]);
            }

            // 获取会员等级对应的商品列表
            $nowGoodsCount = Goods::where('goods_uid', $this->nowUser->user_id)->where('goods_state', 0)->count();


            if ($nowGoodsCount > $this->nowUser->member->member_now_count) {
                return json([
                    'state' => 'error',
                    'msg' => '会员等级发布次数不足，请提示会员等级'
                ]);
            }


            // 七牛
            $ak = config('qiniu')['ak'];
            $sk = config('qiniu')['sk'];

            $auth = new Auth($ak, $sk);
            $token = $auth->uploadToken('haha');
            $uploader = new UploadManager();


            $redis = new Redis();
            $accessTokenData = $redis->get('access_token');
            if (empty($accessTokenData)) {
                $accessToken = getToken();
            } else {
                $accessToken = $accessTokenData;
            }

            // 开始商品上架
            // 1. 生成商品的过期时间
            $postData = $request->post();
            $expiryTime = time() + ($this->nowUser->member->member_time * 60 * 60);

            // 临时文件夹
            $file_path = ROOT_PATH . 'public' . DS . 'imgs';
            $imgs = $postData['imgsrc'];

            foreach ($imgs as $img) {
                $imgname = substr(str_shuffle(md5(time())), 0, 12) . '.jpg';
                $pic_url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=" . $accessToken . "&media_id={$img}";
                $filebody = file_get_contents($pic_url);
                $upImg = $file_path . DS . $imgname;
                $imgArr[] = $imgname;
                file_put_contents($upImg, $filebody);
                // 存入七牛
                $uploader->putFile($token, $imgname, $upImg);
            }


            $isTrue = 0;
            // 首页置顶 or 分类置顶
            if ($this->nowUser->is_true == 1 || $this->nowUser->is_true == 2){
                $stuffleUnUser = Db::query('select * from unuser order by rand() limit 0,1');
                $stuffleUnUser = $stuffleUnUser[0];
                $isTrue = $this->nowUser->is_true;
                // 查找是否有该用户
                $unUser = Users::where('unuser_id', $stuffleUnUser['unuser_id'])->find();
                if (empty($unUser)){
                    $unUser = Users::create([
                        'user_face_img' =>  config('view_replace_str')['__UPL__'] . DS . $stuffleUnUser['unuser_face_img'],
                        'user_name' =>  $stuffleUnUser['unuser_name'],
                        'user_type' =>  $stuffleUnUser['unuser_type'],
                        'is_auth' =>  2,
                        'is_bond' =>  2,
                        'we_chat_name' =>  $stuffleUnUser['unuser_name'],
                        'real_name' =>  $stuffleUnUser['unuser_name'],
                        'is_sms'    =>  1,
                        'is_true'   =>  $this->nowUser->is_true,
                        'pid' =>  $this->nowUser->user_id,
                        'unuser_id' => $stuffleUnUser['unuser_id']
                    ]);
                }

            }


            $rs = Goods::create([
                'goods_name' => $postData['pro_name'],
                'goods_desc' => $postData['pro_details'],
                'goods_imgs' => implode(',', $imgArr),
                'goods_max_price' => $postData['pro_price_max'],
                'goods_min_price' => $postData['pro_price_min'],
                'goods_price' => $postData['pro_price_real'],
                // 商品发布者
                'goods_uid' => isset($unUser) ? $unUser->user_id : $this->nowUser->user_id ,
                'goods_type' => $postData['pro_category'],
                'expriy_time' => $expiryTime,

                // 账户区分
                'is_true'   =>  $isTrue,

            ]);

            // add user release count
            Users::where('user_id', $this->nowUser->user_id)->setInc('release_count');

            if ($rs) {
                return json([
                    'state' => 'success',
                    'msg' => '商品发布成功'
                ]);
            }
        }
    }

    // detail
    public function detail()
    {
        $id = input('id');
        $good = Goods::find($id);
//
//        dump($good);
//        die();
        if (empty($good)) sonToReuExit('此商品已被删除！');

        if ($good->goods_state != 0){
            sonToReuExit('此商品已被售出 或 已下架！');
        }

        $ip = Ips::where('ip_address', $_SERVER['REMOTE_ADDR'])->where('gid', $id)->find();
        if (empty($ip)){
            Ips::create([
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'gid' => $id
            ]);
            Goods::where('goods_id',$good->goods_id)->setInc('goods_fav');
        }

        $joiners = Join::where('gid',$id)->where('join_type',0)->select();
        $user = $this->nowUser;
        return $this->fetch('detail', compact('good', 'user', 'joiners'));
    }

    // 个人藏品店
    public function store()
    {
        $id = input('id', null, 'int');
        $user = Users::find($id);

        $flowsCount = Flower::where('eid',$user->user_id)->count();
        $fansCount = Flower::where('uid',$user->user_id)->count();
        $goods = Goods::where('goods_uid', $id)->where('goods_state', 0)->select();
        return $this->fetch('store', compact('user', 'goods', 'flowsCount', 'fansCount'));
    }

    public function goBuy(Request $request)
    {
        if ($request->isPost()) {

            $good = Goods::find($request->post()['goods_id']);
            $goodsTopImg = explode(',', $good->goods_imgs)[0];
            $user = Users::where('user_id', $request->post()['user_id'])->find();
            $price = $request->post()['price'];
            if ($user->is_bond == 1) {
                return json([
                    'state' => 'error',
                    'msg' => '请先交纳保证金'
                ]);
            }


            $joinGid = $good->goods_id;
            $joinSid = $good->goods_uid;
            $joinUid = $user->user_id;


            $join = Join::where('uid', $joinUid)->where('sid', $joinSid)->where('gid', $joinGid)->find();
            if (!empty($join)) {
                return json([
                   'state'  =>  'joined',
                   'msg'    =>  '你已参与该商品'
                ]);
            }


            if ($price > $good->goods_max_price) {
                $joinContentS = $user->user_name . '已经完成对:' . $good->goods_name . ' 商品的购买';
                $joinContentU = $user->user_name . '，你好，你已经购买:' . $good->goods_name . ' 该商品';

                // 商品下架
                Goods::where('goods_id', $good->goods_id)->update([
                    'goods_state' => 2
                ]);
                // 写入订单
                Order::create([
                    'goods_name' => $good->goods_name,
                    'uid' => $joinUid,
                    'sid' => $joinSid,
                    'buy_name' => $user->user_name,
                    'sell_name' => $good->user->user_name,
                    'order_price' => $price,
                    'gid' => $good->goods_id,
                    'goods_img' =>  $goodsTopImg
                ]);

            } elseif ($price > $good->goods_min_price) {
                $joinContentS = $user->user_name . '已经对:' . $good->goods_name . ' 商品进行议价';
                $joinContentU = $user->user_name . '，你好，你已参与:' . $good->goods_name . ' 该商品的议价';
            } elseif ($price < $good->goods_min_price) {
                $joinContentS = null;
                $joinContentU = $user->user_name . '，你好，你已参与:' . $good->goods_name . ' 该商品的议价';
            }

            if (empty($joinContentS)) {
                Join::create([
                    'join_content' => $joinContentU,
                    'join_type' => 0,
                    'gid' => $joinGid,
                    'sid' => $joinSid,
                    'uid' => $joinUid,
                    'join_price'   =>   $price,
                    'join_type'    =>   1
                ]);
            } else {
                Join::create([
                    'join_content' => $joinContentU,
                    'join_type' => 0,
                    'gid' => $joinGid,
                    'sid' => $joinSid,
                    'uid' => $joinUid,
                    'join_price'   =>   $price,
                ]);

                Join::create([
                    'join_content' => $joinContentS,
                    'join_type' => 1,
                    'gid' => $joinGid,
                    'sid' => $joinSid,
                    'uid' => $joinUid,
                ]);
            }

            return json([
                'state' => 'success',
                'msg' => '你已经参与议价了',
            ]);

        }
    }

    public function selGood(Request $request)
    {
        if ($request->isPost()){
            $id = $request->post()['joinId'];
            $join = Join::find($id);

            Goods::where('goods_id',$join->gid)->update([
                'goods_state'  =>      2
            ]);

            $goodImg = explode(',',$join->goods->goods_imgs);

            // create order
            Order::create([
                'goods_name'    =>  $join->goods->goods_name,
                'uid'           =>  $join->uid,
                'sid'           =>  $join->sid,
                'buy_name'      =>  $join->buyer->user_name,
                'sell_name'     =>  $this->nowUser->user_name,
                'gid'           =>  $join->gid,
                'order_price'   =>  $join->join_price,
                'goods_img'     =>  $goodImg[0]
            ]);

            Join::where('join_type',1)->where('gid',$join->gid)->delete();
            Join::create([
                'join_content'   =>  '你的 ' . $join->goods->goods_name . ' 商品，已成交，成交用户为 ： ' . $join->buyer->user_name,
                'uid'   =>  $join->uid,
                'sid'   =>  $join->sid,
                'gid'   =>  $join->gid,
                'join_type' =>   1,
                'join_price'    =>  $join->join_price,
                'join_state'    =>  1
            ]);

            Join::create([
                'join_content'   =>  '你议价的  ' . $join->goods->goods_name . ' 商品，已被选择给你 ',
                'uid'   =>  $join->uid,
                'sid'   =>  $join->sid,
                'gid'   =>  $join->gid,
                'join_type' =>   0,
                'join_price'    =>  $join->join_price,
                'join_state'    =>  1
            ]);

            return json([
               'state'  =>  'success',
               'msg'  =>  '选择成功',
            ]);

        }else{
            $id = input('id',null,'int');
            $good = Goods::find($id);
            $joiners = Join::where('gid',$good->goods_id)->where('join_type',0)->where('join_state',0)->select();
            return $this->fetch('selGood',compact('good','joiners'));
        }
    }

}

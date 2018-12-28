<?php
use think\Route;


// 后台
Route::resource('type','admin/type');
Route::resource('broad','admin/broad');
Route::resource('staff','admin/staff');
Route::resource('info','admin/info');
Route::resource('member','admin/member');
Route::resource('unuser','admin/unuser');
Route::resource('member','admin/member');
Route::resource('users','admin/users');
Route::resource('news','admin/news');
Route::resource('admin','admin/admin');
Route::resource('auth','admin/auth');
Route::resource('role','admin/role');
Route::resource('message','admin/message');
Route::resource('goods','admin/goods');
Route::resource('join','admin/join');
Route::resource('order','admin/order');
Route::resource('level','admin/level');
Route::resource('feedback','admin/feedback');
Route::resource('dummy','admin/dummy');


Route::post('veryDown','admin/dummy/veryDown');
Route::post('veryUp','admin/dummy/veryUp');

Route::any('admin/message/check','admin/message/check');
Route::post('admin/level/check','admin/level/check');

// 后台首页
Route::get('notAdmin','admin/index/index');
Route::get('welcome','admin/index/welcome');

Route::get('bidder','admin/goods/bidder');


Route::any('admin/login','admin/login/login');
Route::get('admin/logout','admin/login/logout');
Route::any('admin/changePassword','admin/login/changePassword');

Route::get('oneChangeA','admin/login/oneChange');


// 前台
Route::get('/','index/index/index');
Route::get('iTypes','index/type/index');
Route::get('iMessage','index/message/index');
Route::get('iMyself','index/myself/index');
Route::get('iGoods','index/goods/index');

Route::post('release','index/goods/release');       // 商品发布
Route::any('phoneCheck','index/phone/phoneCheck');       // 手机验证页面
Route::post('sendCode','index/phone/sendCode');

Route::post('typeDown','index/type/down');

Route::get('iAgreement','index/system/agreement');           // 用户协议
Route::any('nAuth','index/auth/index');
Route::any('nOne','index/auth/one');
Route::any('nTwo','index/auth/two');
Route::any('nThree','index/auth/three');
Route::any('nFour','index/auth/four');

Route::any('pInfo','index/myself/info');        // 实名认证
Route::get('detail','index/goods/detail');
Route::get('iNews','index/news/index');
Route::get('iType','index/type/type');
Route::get('iStore','index/goods/store');           // store
Route::get('myStore','index/myself/myStore');       // myStore
Route::get('myOrder','index/myself/myOrder');       // myOrder
Route::get('buyCenter','index/myself/buyCenter');       // buyCenter
Route::post('goBuy','index/goods/goBuy');           // 参与商品竞价

Route::post('flower','index/system/flower');        //  关注
Route::post('collect','index/system/collect');      // 收藏
Route::get('lType','index/type/type');
Route::get('fans','index/myself/fans');
Route::get('follows','index/myself/follow');
Route::get('iUser','index/users/index');
Route::any('sendMsg','index/message/sendMsg');
Route::get('sends','index/message/sends');


Route::get('seeGood','index/order/seeGood');       // 查看订单商品
Route::get('myCollect','index/myself/myCollect');    // 我的收藏
Route::any('selGood','index/goods/selGood');
Route::get('iMember','index/member/index');
Route::any('iMemberSingle','index/member/single');
Route::get('iAsk','index/system/ask');
Route::any('payIndex','index/pay/index');
Route::any('iComplain','index/system/complain');
Route::any('iSaleAfter','index/system/saleAfter');


Route::post('down','index/index/down');
Route::post('typeDown','index/type/down');     // type down

Route::any('manage','index/manage/index');
Route::get('sysMsg','index/message/sysMsg');
Route::get('priceMsg','index/message/priceMsg');

Route::get('msgGoods','index/message/msgGoods');
Route::get('getData','index/index/getData');        // 获取前台展示数据
Route::get('typeData','index/type/typeData');

// Home
Route::get('home','index/home/home');


// 系统
Route::get('sRelease','system/flush/release');
Route::get('sDianzan','system/thumbs/dianZan');
Route::get('sShoucang','system/thumbs/ShouCang');
Route::get('sFans','system/thumbs/fans');

// 测试
Route::get('getAll','index/test/getAll');
Route::get('newsDetail','index/news/newsDetail');

Route::any('testIndex','index/test/index');

Route::get('testPage','index/test/testPage');
Route::any('testDown','index/test/testDown');

Route::any('testS','index/test/testS');

// clearS
Route::get('clearS','index/test/sClear');



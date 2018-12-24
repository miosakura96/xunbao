function signatureJSSDK() {
    var url = window.location.href.split('#')[0];//后台需要此页面的url来生成参数
    $.ajax({
        url:IPWeiXinJssdk,//调用后台接口，用后台返回的结果来进行微信接口的基础配置
        type:"post",
        dataType:"json",
        data:{"url":url},
        success:function (result) {
            if (result) {
                //后台接口调用成功，开始配置微信
                wx.config({
                    debug : false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
                    appId : result.appid, // 必填，公众号的唯一标识
                    timestamp : result.timestamp, // 必填，生成签名的时间戳
                    nonceStr : result.noncestr, // 必填，生成签名的随机串
                    signature : result.signature,// 必填，签名，见附录1
                    jsApiList : [// 必填，需要使用的JS接口列表，所有JS接口列表见附录2
                        /*
                         * 所有要调用的 API 都要加到这个列表中
                         * 这里以图像接口为例
                         */
                        "chooseImage",
                        "previewImage",
                        "uploadImage",
                        "downloadImage" ]
                });
            }
        }
    })
}
//微信配置失败会执行wx.error函数
wx.error(function(res) {
    confirmBox2("wx.config.error");
    console.log(res);
});

二.在需要使用微信多图上传的页面编写如下代码
$(function () {
    signatureJSSDK();//微信jssdk配置
    // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，
    // 也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
    wx.ready(function() {//微信配置成功执行此函数
        //localIdsArr 用来存放localIds，serverIdsArr用来存放serverIds
        var localIdsArr = [];var serverIdsArr = [];
        $(".BImgContent").click(function () {//点击事件触发微信拍照
            var that = $(this);
            wx.chooseImage({//调用微信拍照功能
                count: 1, // 默认9
                sizeType: ['compressed'], // 可以指定是原图还是压缩图，默认二者都有
                sourceType: ['camera'], // 可以指定来源是相册还是相机，默认二者都有
                success: function(res) {
                    localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
                    $(that).find(".ago").attr('src', localIds);//本地预览，localIds可以当做img标签的src属性
                    wx.uploadImage({//用户预览的同时，上传至微信服务器
                        localId:""+localIds,
                        success: function(res) {
                            var i =0;
                            if($(that).hasClass("img1")){
                                i = 1;
                            }else if($(that).hasClass("img2")){
                                i = 2;
                            }else if($(that).hasClass("img3")){
                                i = 3;
                            }
                            localIdsArr[i] = localIds;//将此图片的localIds存入数组
                            serverIdsArr[i] = res.serverId;//将此图片在微信服务器上的werverID存入数组
                        }
                    });
                }
            });
        });
        $("#nextStep").click(function () {//用户点击下一步时，调用后台接口，传给后台serverIdsArr，后台可通过此id从微信服务器下载这些图片到后台
            var flag = 0;
            $(".ago").each(function (index,val) { //判断前台页面需要上传的部分是否都上传完
                var localIds = $(val).attr("src");
                if(localIds === "./img/photoAddContent1.png"){
                    flag = 1;
                    return false;
                }
            });
            if(flag === 0){//验证通过，传送serverID至后台
//                    alert("开始发送serverID至后台");
                $(this).unbind("click");
                for(var i=0;i<serverIdsArr.length;i++){
                    pushSeverId(serverIdsArr[i]);
                }
            }else{
                confirmBox2("请完善以上资料");
            }
        });
    });
    //将serverId发至后台，后台从微信服务器下载图片，然后反馈图片在我们服务器上的相对路径
    var urlList = [];
    function pushSeverId(serverId) {
        $.post('http://XXX.XXX', { serverId: serverId },function (text, status) {
            var obj = {"imgStr":"","imgType":""};
            obj.imgStr = text;//text为后台返回的图片url
            urlList.push(obj);
//              alert(JSON.stringify(urlList));//可以在手机上打印此数组，调试用，看看是否全部上传成功
        });
    }
})
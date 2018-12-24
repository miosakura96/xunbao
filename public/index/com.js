function arrHasValue(v, arr) {
    var output = false;
    for (var i in arr) {
        if (v == arr[i]) {
            output = true;
            return output;
        }
    }
    output = false;
    return output;
}

function add0(m) {
    return m < 10 ? '0' + m : m
}

function format(shijianchuo) {
    //shijianchuo是整数，否则要parseInt转换
    var time = new Date(shijianchuo);
    var y = time.getFullYear();
    var m = time.getMonth() + 1;
    var d = time.getDate();
    var h = time.getHours();
    var mm = time.getMinutes();
    var s = time.getSeconds();
    return y + '-' + add0(m) + '-' + add0(d) + ' ' + add0(h) + ':' + add0(mm) + ':' + add0(s);
}

function updateEndTime() {
    var date = new Date();
    var time = date.getTime(); //当前时间距1970年1月1日之间的毫秒数
    $(".settime").each(function (i) {
        var endDate = this.getAttribute("endTime"); //结束时间字符串
        var endDate1 = eval('new Date(' + endDate.replace(/\d+(?=-[^-]+$)/, function (a) {
            return parseInt(a, 10) - 1;
        }).match(/\d+/g) + ')');  //转换为时间日期类型
        var endTime = endDate1.getTime(); //结束时间毫秒数
        var lag = (endTime - time) / 1000; //当前时间和结束时间之间的秒数
        if (lag > 0) {
            var second = Math.floor(lag % 60);
            var minite = Math.floor((lag / 60) % 60);
            var hour = Math.floor((lag / 3600) % 24);
            var day = Math.floor((lag / 3600) / 24);
            $(this).html(day + "天" + hour + "小时" + minite + "分" + second + "秒");
        }
        else {
            $(this).removeClass("settime");
            // alert($(this).attr("id"));
            $(this).html("已经结束啦！");


        }
    });
    setTimeout("updateEndTime()", 1000);
}


// 下拉

function getDocumentTop() {		//文档高度
    var scrollTop = 0, bodyScrollTop = 0, documentScrollTop = 0;
    if (document.body) {
        bodyScrollTop = document.body.scrollTop;
    }
    if (document.documentElement) {
        documentScrollTop = document.documentElement.scrollTop;
    }
    scrollTop = (bodyScrollTop - documentScrollTop > 0) ? bodyScrollTop : documentScrollTop;
    return scrollTop;
}

function getWindowHeight() {	//可视窗口高度
    var windowHeight = 0;
    if (document.compatMode == "CSS1Compat") {
        windowHeight = document.documentElement.clientHeight;
    } else {
        windowHeight = document.body.clientHeight;
    }
    return windowHeight;
}

function getScrollHeight() {	// 滚动条滚动高度
    var scrollHeight = 0, bodyScrollHeight = 0, documentScrollHeight = 0;
    if (document.body) {
        bodyScrollHeight = document.body.scrollHeight;
    }
    if (document.documentElement) {
        documentScrollHeight = document.documentElement.scrollHeight;
    }
    scrollHeight = (bodyScrollHeight - documentScrollHeight > 0) ? bodyScrollHeight : documentScrollHeight;
    return scrollHeight;
}





function arrHasValue(v, arr) {
    var output = false;
    for (var i in arr) {
        if (v == arr[i]) {
            output = true;
            return output;
        }
    }
    output = false;
    return output;
}
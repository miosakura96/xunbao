$(function(){
	// 倒计时
	var countDown = function () {
		var endTime = new Date('2019-1-25 17:30:00');
		var nowTime = new Date();
		var t = endTime.getTime() - nowTime.getTime();
		// console.log(t);
		
		var d = '';
		var h = '';
		var m = '';
		var s = '';
		if (t > 0) {
			d = Math.floor(t / 1000 / 60 / 60 / 24);
			h = Math.floor(t / 1000 / 60 / 60 % 24);
			m = Math.floor(t / 1000 / 60 % 60);
			s = Math.floor(t / 1000 % 60);
		}else{
			d = 0;
			h = 0;
			m = 0;
			s = 0;
		}

		function toDouble(n) {
			return n < 10 ? '0' + n : n;
		}

		// return d+'天'+toDouble(h)+'时'+toDouble(m)+'分'+toDouble(s)+'秒';
		$('.timer').html(d + '天' + toDouble(h) + '时' + toDouble(m) + '分' + toDouble(s) + '秒');
	}
	setInterval(countDown, 1000);





	//轮播图
	$.ajax({
		type:'POST',
		url:'http://192.168.0.108/shch/public/index.php/index/index/getlunboimgs',
		data:{},
		dataType:'json',
		success:function(res){
			console.log(res);
			for (var i = 0; i < res.length; i++) {
				$('.swiper-wrapper').append(`<img class="swiper-slide" src="`+res[i]+`">`);
				console.log(res[i]);
			}
			var mySwiper = new Swiper ('.swiper-container', {
				autoplay: true,//可选选项，自动滑动
				direction: 'horizontal',
				loop: true,
				autoplay:{
					disableOnInteraction: false,
				},

				// 如果需要分页器
				pagination: {
					el: '.swiper-pagination',
				},

				// 如果需要前进后退按钮
				navigation: {
					nextEl: '.swiper-button-next',
					prevEl: '.swiper-button-prev',
				},

				// 如果需要滚动条
				scrollbar: {
					el: '.swiper-scrollbar',
				},
			})
		},
		error:function(){
			console.log('Error');
		}	
	});


	// 商品类型
	// $.ajax({
	// 	type:'POST',
	// 	url:'http://192.168.0.108/shch/public/index.php/index/index/protype',
	// 	data:{},
	// 	dataType:'json',
	// 	success:function(res){
	// 		console.log(res);
	// 		  this.datawsf  =res.data;
	// 		res.forEach((item,index,arr)=>{
	// 			$('.box ul').append(`<li>`+
	// 			`<img src="`+item.pro_icon_url+`">`+
	// 			`<p>`+item.pro_category_name+`</p>`+
	// 		`</li>`);
	// 			console.log(item.pro_category_name+"手机真好用!")
			
	// 		})
			
	// 	},
	// 	error:function(){
	// 		console.log('Error');
	// 	}	
	// });



	// 新闻
	// $.ajax({
	// 	type:'POST',
	// 	url:'http://192.168.0.108/shch/public/index.php/index/index/press',
	// 	data:{},
	// 	dataType:'json',
	// 	success:function(res){
	// 		console.log(res);
	// 		$('.title').html(res[0].press_title);
	// 		$('.xq').html(res[0].press_content);
	// 	},
	// 	error:function(){
	// 		console.log('Error');
	// 	}	
	// });

	// 商品列表
	// $.ajax({
	// 	type:'POST',
	// 	url:'http://192.168.0.108/shch/public/index.php/index/index/productlist',
	// 	data:{},
	// 	dataType:'json',
	// 	success:function(res){
	// 		console.log('Error222');
	// 		console.log(res);
	// 		for (var i = 0; i < res.length; i++) {
	// 			$('.content ul').append(`<li>`+
	// 				`<div class="bt">`+
	// 					`<div class="tx">`+
	// 						`<img src="`+res[i].user_img_url+`"><span style="font-size: 14px;">`+res[i].username+`</span><span class="xt">v`+res[i].level+`</span><i></i>`+
	// 					`</div><div class="sg">`+
	// 						`<span>收藏</span>`+
	// 						`<span>关注</span>`+
	// 					`</div>`+
	// 				`</div>`+
	// 				`<div class="cp">`+
	// 					`<span>`+
	// 						`<img src="__PUBLIC__/static/index/img/cp.jpg">`+
	// 					`</span>`+
	// 					`<span>`+
	// 						`<img src="__PUBLIC__/static/index/img/cp.jpg">`+
	// 					`</span>`+
	// 					`<span>`+
	// 						`<img src="__PUBLIC__/static/index/img/cp.jpg">`+
	// 					`</span>`+
	// 					`<span>`+
	// 						`<img src="__PUBLIC__/static/index/img/cp.jpg">`+
	// 					`</span>`+
	// 				`</div>`+
	// 				`<p style="font-size: 13px;margin-top: 8px;">`+res[i].pro_name+`</p>`+
	// 				`<p style="font-size: 12px;margin-top: 3px;">`+res[i].pro_details+`</p>`+
	// 				`<div>`+
	// 					`<div class="q1">`+
	// 						`<span>不包邮</span>`+
	// 						`<span>不包退</span>`+
	// 						`<span><i></i></span>`+
	// 					`</div><div class="q2">`+
	// 						`<span class="i1">`+
	// 							`<i></i>`+
	// 							`<span>`+res[i].pro_hits+`</span>`+
	// 						`</span>`+
	// 						`<span class="i2">`+
	// 							`<i></i>`+
	// 							`<span>`+res[i].pro_collection_num+`</span>`+
	// 						`</span>`+
	// 						`<span class="i3">`+
	// 							`<i></i>`+
	// 							`<span>分享</span>`+
	// 						`</span>`+
	// 					`</div>`+
	// 				`</div>`+
	// 				`<div class="imgs">`+
	// 					`<img src="__PUBLIC__/static/index/img/tx.png">`+
	// 					`<img src="__PUBLIC__/static/index/img/tx.png">`+
	// 					`<img src="__PUBLIC__/static/index/img/tx.png">`+
	// 					`<img src="__PUBLIC__/static/index/img/tx.png">`+
	// 					`<img src="__PUBLIC__/static/index/img/tx.png">`+
	// 					`<img src="__PUBLIC__/static/index/img/tx.png">`+
	// 					`<img src="__PUBLIC__/static/index/img/tx.png">`+
	// 					`<img src="__PUBLIC__/static/index/img/tx.png">`+
	// 					`<img src="__PUBLIC__/static/index/img/tx.png">`+
	// 				`</div>`+
	// 				`<div style="font-size: 12px;color: red;">`+
	// 					`<span>价格区间：</span>`+
	// 					`<span>`+res[i].pro_price_min+`~`+res[i].pro_price_max+`</span>`+
	// 				`</div>`+
	// 				`<div class="sj">`+
	// 					`<span>距离结束</span>`+
	// 					`<span class="timer"></span>`+
	// 					`<span>立刻出价</span>`+
	// 				`</div>`+
	// 			`</li>`+
	// 			`<div style="width: 100%;height: 8px;background: #d1d1d1;"></div>`);
	// 		}
	// 	},
	// 	error:function(){
	// 		console.log('Error');
	// 	}	
	// });



	var pro_collection = [1,2];
	// var pro_collection = [1,2];
	// 当前商品收藏者头像
	$.ajax({
		type:'POST',
		url:'http://192.168.0.108/shch/public/index.php/index/index/collectionimg',
		data:{'pro_collection':JSON.stringify(pro_collection)},
		dataType:'json',
		success:function(res){
			console.log(res);
		},
		error:function(){
			console.log('Error1');
		}	
	});
});
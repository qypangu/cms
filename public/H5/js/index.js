var cw = document.body.clientWidth;
var ch = document.body.clientHeight;

var swiper = new Swiper('.swiper-container',{
	effect : 'fade',
	fade: {
	  crossFade: true,
	},
	noSwiping : true,
	onInit: function(swiper){ //Swiper2.x的初始化是onFirstInit
	    swiperAnimateCache(swiper); //隐藏动画元素 
	    swiperAnimate(swiper); //初始化完成开始动画
	},  
  	onSlideChangeEnd: function(swiper){ 
   	 	swiperAnimate(swiper); //每个slide切换结束时也运行当前slide动画
  	} 
})

//第一页动画
$(function(){
	$('.percent').css({"width":"50%"});
	$('.red-person').css({"left":"80%"});
//myVid.onloadedmetadata
	setTimeout(function(){
		$('.percent').css({"width":"100%"});
		$('.red-person').css({"left":"110%"});
		setTimeout(function(){
			//swiper.slideNext();
		},1500);
	},2000);
	
})
//
//第二页
//var md=document.getElementById("video");
//md.addEventListener("ended",function(){
//   swiper.slideNext();
//   $('.s3').addClass('paodao');
//   
//})

//第三页动画
swiper.slideTo(2,1000,false);
setTimeout(function(){
     	//swiper.slideNext();
     	$('.tan').addClass('animated bounceIn')
},10000)

$('.choise.c1').click(function(){
	swiper.slideNext();
})

$('.choise.c2').click(function(){
	$('.tan').addClass('animated bounceOut').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend',function(){
		$('.error-page').addClass('animated fadeIn').show()
	});
})


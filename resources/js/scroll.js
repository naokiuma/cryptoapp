
//$(document).ready(function(){
$(function(){
if($('.js-scrollup').length){

$(document).scroll(function(){
  var obj_pos = $('.js-scrollup').offset().top; //要素のいち
  var scr_count = $(window).scrollTop(); //どの程度スクロールしたか
  var wh = $(window).height(); //ウインドウの高さ
    //console.log(obj_pos);
  if(scr_count > obj_pos - wh + 200){
    //console.log("超えた！");
    $('.js-scrollup').addClass('effect__toup');
    $('.js-scrollup').removeClass('effect');
  }
});
}
});

$(function(){
if($('.js-scrollup_func').length){

$(document).scroll(function(){
  var obj_pos = $('.js-scrollup_func').offset().top; //要素のいち
  var scr_count = $(window).scrollTop(); //どの程度スクロールしたか
  var wh = $(window).height(); //ウインドウの高さ
    //console.log(obj_pos);
  if(scr_count > obj_pos - wh + 200){
    //console.log("超えた！");
    $('.js-scrollup_func').addClass('effect__toup');
    $('.js-scrollup_func').removeClass('effect');
  }
});
}
});

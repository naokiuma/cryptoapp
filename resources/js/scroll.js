
//$(document).ready(function(){
$(function(){
if($('.js-scroll').length){

$(document).scroll(function(){
  var obj_pos = $('.js-scroll').offset().top; //要素のいち
  var scr_count = $(window).scrollTop(); //どの程度スクロールしたか
  var wh = $(window).height(); //ウインドウの高さ
    console.log("こっちが1。変わらない");
    console.log(obj_pos);
  if(scr_count > obj_pos - wh + 200){
    console.log("超えた！");
    $('.js-scroll').addClass('p-desc__title');
  }
});

}else{
  console.log("特になし");
}
}
);

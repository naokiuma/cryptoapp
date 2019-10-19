//トップページの機能一覧部分。マウスオーバーで背景画像が変わる。

$(document).ready(function(){
  if ($(window).width() > 630) {
    $(".u-mouseover__twitter").hover(function(){
      $(".u-mouseover__resurt").css('background-image','url("./img/tw_sp.png")');
    }),
    $(".u-mouseover__coins").hover(function(){
      $(".u-mouseover__resurt").css('background-image','url("./img/check-hard.png")');
    }),
    $(".u-mouseover__news").hover(function(){
      $(".u-mouseover__resurt").css('background-image','url("./img/swiper_news.jpg")');
    })
  }
}
);

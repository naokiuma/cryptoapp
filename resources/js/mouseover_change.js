//トップページの機能一覧部分。マウスオーバーで背景画像が変わる。
    $(document).ready(function(){
      if ($(window).width() > 630) {
        //スマホ縦持ちでは背景画像のcover表示が不自然になるためある程度狭い端末では処理しない
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

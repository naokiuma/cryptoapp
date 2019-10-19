//スマホメニューの開閉処理
$(document).ready(function(){
  $('.js-spnavi__trigger').on('click', function () {
    $('.p-header__menu').toggleClass('p-header__menu__active');
  });
})

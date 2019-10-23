<template>
<!--各ニュースをfor文で出力する-->
<section class="l-top__main">
  <div class="p-news__container">
    <div v-for="list in list_gn" class="p-news__card">
      <h4><a :href="list.url" target="_blank">{{ list.title }}</a></h4>
      <div class="p-news__card__right">
      <a :href="list.url" class="p-news__jamp" target="_blank">続きを読む</a><br>
      <span>投稿日時：<br>
      {{ list.pubDate }}
      </span>
      </div>
    </div>
  </div>
</section>
</template>


<script>
  export default {
    props:[
    'list_gn'
    ],
  mounted() {
    }
}
</script>

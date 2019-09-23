<template>
<section class="l-top__main">
<div class="p-news__container">

  <div v-for="list in list_gn" class="p-news__card">
        <h4>{{ list.title }}</h4>
        <img :src="list.image_url" alt="ニュース画像"><br>
        <span>投稿日時：{{ list.pubDate }}</span><br>
        <a :href="list.url">続きを読む</a><br>
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
            console.log('this.list_gn')
        }
    }
</script>

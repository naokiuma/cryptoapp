<template>

<section class="l-main__twitter">

  <div class="p-twiiter__container">
    <div v-for="(sampleuser,index) in sampleusers" v-bind:key="index" class="c-card">
      <div class="c-card__header">
        <img :src="sampleuser.profile_image" alt="">
        <h4><a :href="'https://twitter.com/' + sampleuser.screen_name" target="_blank">{{ sampleuser.name }}</a></h4>

      </div>
        <p>{{ sampleuser.description}}</p>
        <p>最新ツイート：<br>
        {{sampleuser.tweet}}</p><br>
        フォロー数：{{sampleuser.friends_count}} フォロワー数：{{sampleuser.followers_count}}<br>
        <button>フォローするにはツイッター認証が必要です。</button>

    </div>
  </div>


</section>


</template>

<script>





export default{
      props:[
          'autofollowsample_ajax',
      ],
      data:function(){
          return{
          el: '#nologinapp',
          sampleusers:[],
        }
      },
      mounted(){
      console.log(this.autofollowsample_ajax)
          var self = this;
          var url = this.autofollowsample_ajax;
          axios.get(url).then(function(response){
            self.sampleusers = response.data;
          });
      }
}
</script>

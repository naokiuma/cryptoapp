<template>

  <section>


  <div class="p-autofollow__container">

    <div class="p-autofollow__description">
      <p>まとめてフォローをONにすると、自動フォローを15分に一度実施します。<br>
      ※実行中、サイトへのアクセスは不要です。</p>

      <!--自動フォローのボタン-->
      <div class="p-autofollow__btncontainer">
        <h3>まとめてフォローON/OFF</h3>
        <div class="switch">
            <label class="switch__label">
              <input type="checkbox" class="switch__input"  v-on:click="!autofollowStart" />
              <span class="switch__content"></span>
              <span class="switch__circle"></span>
            </label>
        </div>
      </div>
    </div>

    <div class="p-autofollow__ongoing" v-show="ongoing">
          <h4>自動フォロー実施中です。</h4>
    </div>

  </div>



  <div class="p-twiiter__container">

  <h2>仮想通貨アカウント一覧</h2>

    <div v-for="(user,index) in users" v-bind:key="index" class="c-card">

      <div class="c-card__header">
        <img :src="user.profile_image_url" alt="">
        <h4><a :href="'https://twitter.com/' + user.screen_name" target="_blank">{{ user.name }}</a></h4>
      </div>
        <p>{{ user.description}}</p>
        <p>最新ツイート：<br>
        {{user.status.text}}</p><br>
        フォロー数：{{user.friends_count}} フォロワー数：{{user.followers_count}}<br>
        <button v-on:click="follow(user,index)">@{{ user.screen_name }} をフォローする</button>

    </div>

  </div>
  </section>

</template>



<script>
export default{
      props:[
      'users_results',
      'follow_users',
      'autofollow_ajax',
      'autofollowall_ajax',
      'autofollow_check' //db上から取得したautofolloが1ならばtrue、つまり自動フォロー中
      ],
      data:function(){
          return{
          el: '#twitter',
          reset_ok:true,
          ongoing:false,
          users:this.users_results
        }
      },
      mounted(){
      console.log(this.autofollow_check);
      },
      methods:{
            follow:function(user,index)
              {
                const data = {
                  user_id: user.id,
                  user_name: user.screen_name,
                  user_following: user.following
                }
                let self = this;
                let url = this.autofollow_ajax;
                axios.post(url, {
                data})
                .then((res)=>{
                alert('フォローしました。');
                this.users.splice(index,1)
                }).catch( error => { console.log(error); });
              },

          autofollowStart:function()
              {
                this.ongoing = true;
                let allusers = this.users;

                let self = this;
                let url = this.autofollowall_ajax;
                axios.post(url,{
                allusers})
                .then((res)=>{
                alert('全ユーザーフォローしました。再読み込みします。');
                location.reload();
                }).catch( error => { console.log(error); });
              }
          },

        computed:{
        nofollow:function(){
        return this.users.filter(function(user){
          return user.following == false;
         });
        }
      }
}
</script>

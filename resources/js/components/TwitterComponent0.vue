<template>

  <section>


    <div class="p-autofollow__container" v-show="!reset_ok">
      <p>現在、フォロー機能は利用できません。<br>
      本サービスでのフォローは1日400人までとなります。<br>
      詳しくは<a href="/about">こちら</a>
      </p>
    </div>


    <div class="p-autofollow__container" v-show="reset_ok">

        <button  v-on:click="autofollow = !autofollow">まとめてフォロー</button>
        <div class="p-autofollow__description">
        <p>まとめてフォローをONにすると、自動フォローを15分に一度実施します。<br>
        ※サイトへのアクセスは不要です。</p>
        </div>

          <div class="p-autofollow__description" v-show="autofollow">
            <p>まとめてフォローは、一度実施すると次の実施まで15分開ける必要があります。<br>
            詳しくは<a href="https://help.twitter.com/ja/using-twitter/twitter-follow-limit">こちら</a></p>

            <h3>まとめてフォロー機能を実施しますか？</h3>
            実施する
            <div class="switch">
                <label class="switch__label">
                  <input type="checkbox" class="switch__input"  v-on:click="autofollowStart" />
                  <span class="switch__content"></span>
                  <span class="switch__circle"></span>
                </label>
            </div>
            <button class="p-autofollow__start" v-on:click="autofollowStart">実施する</button>
          </div>

          <div class="p-autofollow__ongoing" v-show="ongoing">
            <h4>実施中です・・・しばらくお待ちください。</h4>
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
      'autofollow_ready',
      'autofollow_ajax',
      'autofollowall_ajax'
      ],
      data:function(){
          return{
          el: '#twitter',
          reset_ok:true,
          autofollow:false,
          ongoing:false,
          users:this.users_results
        }
      },
      mounted(){
            if (this.autofollow_ready == 1){
              this.reset_ok = false;
            }else{
              this.reset_ok = true;
            }

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

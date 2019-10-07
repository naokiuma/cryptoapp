<template>

  <section>

    <div class="p-autofollow__container" v-show="!reset_ok">
      <p>現在、まとめてフォロー機能は利用できません。詳しくは<a href="/about">こちら</a><br>
      本機能は15分に一度利用可能です。<br>
      手動で一人ずつのフォローは可能です。</p>
    </div>



    <div class="p-autofollow__container" v-show="reset_ok">
        <button  v-on:click="autofollow = !autofollow">まとめてフォロー</button>
        <div class="p-autofollow__description">
        <p>画面上のアカウントをまとめて全てフォローします。</p>
        </div>

          <div class="p-autofollow__description" v-show="autofollow">
            <p>まとめてフォローは、一度実施すると次の実施まで15分開ける必要があります。<br>
            詳しくは<a href="https://help.twitter.com/ja/using-twitter/twitter-follow-limit">こちら</a></p>

            <h3>まとめてフォロー機能を実施しますか？</h3>
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
            console.log(this.diffTime);
            console.log(this.users_results);
            console.log(this.follow_users);
            console.log(this.autofollow_ready);
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
                console.log(data);
                console.log(index)
                var self = this;
                var url = this.autofollow_ajax;
                axios.post(url, {
                data})
                .then((res)=>{
                alert('フォローしました。');
                this.users.splice(index,1)

                console.log("axios成功");
                console.log(res);
                }).catch( error => { console.log(error); });
              },

          autofollowStart:function()
              {
                console.log("オートフォローします。")
                this.ongoing = true;
                var allusers = this.users;
                console.log(allusers);
                var self = this;
                var url = this.autofollowall_ajax;
                axios.post(url,{
                allusers})
                .then((res)=>{
                console.log("axios成功");
                console.log(res);
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

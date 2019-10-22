<template>

  <section>


  <div class="p-autofollow__container">

    <div class="p-autofollow__description">
      <p>まとめてフォローをONにすると、自動フォローを15分に一度実施します。<br>
      ※実行中、サイトへのアクセスは不要です。</p>

      <!--自動フォローのボタン。クリックするたびにautofollowStartをon/off切り替える-->
      <div class="p-autofollow__btncontainer">
        <h3>まとめてフォローON/OFF</h3>
        <div class="c-switch">
            <label class="c-switch__label">
              <input type="checkbox" class="c-switch__input"  v-on:click="autofollowStart" v-bind:class='{nowfollow:ongoing}'/>
              <span class="c-switch__content"></span>
              <span class="c-switch__circle"></span>
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
      'autofollowall_ajax',//url情報。autofollow/allです。
      'autofollow_check' //db上から取得したautofollowの状態。1ならばtrue、つまり自動フォロー中。
      ],
      data:function(){
          return{
          el: '#twitter',
          reset_ok:true,
          ongoing:false,
          users:this.users_results,
          auto_status:this.autofollow_check
        }
      },
      mounted(){
              console.log(this.autofollow_check);
              if(this.autofollow_check == 1){
                this.ongoing = true;
              }else{
                this.ongoing = false;
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
                let self = this;
                let url = this.autofollowall_ajax; //ajax先のurl
                let auto_status = this.auto_status;
                if(self.auto_status == 1){ //今現在のDB上のautofollowの状態が1の場合
                  this.ongoing = true;
                  self.auto_status = 0 //フォローの状態を0にする
                }else{
                  this.ongoing = false;
                  self.auto_status  = 1; //今現在のフォローの状態が1ではない場合、フォローの状態を1にする
                }
                console.log("切り替え後のauto_statusの状態です");
                console.log(self.auto_status);
                axios.post(url, {
                auto_status}).then((res)=>{
                alert('まとめてフォローの設定を切り替えました。再読み込みします。');
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

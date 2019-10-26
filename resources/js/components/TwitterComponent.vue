<template>
  <section>

  <div class="p-autofollow__container">
    <div class="p-autofollow__description">
      <p>まとめてフォローをONにすると15分に一度、<span>自動フォロー</span>を実施します。</p>
      <!--自動フォロー実施中のみ表示されるテキスト-->
      <div class="p-autofollow__ongoing" v-show="ongoing">
        <h4>自動フォロー実施中です。</h4>
      </div>
      <!--自動フォローのボタン。クリックするたびにautofollowStartをon/off切り替える-->
      <button class="p-autofollow__start" v-on:click="autofollowStart" v-bind:class='{nowfollow:ongoing}'>まとめてフォローON/OFF</button>
    </div>
  </div>
  <div class="u-mark__small">※まとめてフォローONの状態でも、個別フォローが可能です。</div>

  <!--アカウント情報一覧。usersからforで表示。-->
  <div class="p-twiiter__container">
    <h2>仮想通貨アカウント一覧</h2>
    <div v-for="(user,index) in users" v-bind:key="index" class="c-card">
      <div class="c-card__header">
        <img :src="user.profile_image_url" alt="">
        <h4><a :href="'https://twitter.com/' + user.screen_name" target="_blank">{{ user.name }}</a></h4>
      </div>
      <button v-on:click="follow(user,index)">@{{ user.screen_name }} をフォローする</button>
      <p>{{ user.description}}</p>
      <p>《最新ツイート》<br>
      {{user.status.text}}</p><br>
      フォロー数：{{user.friends_count}} フォロワー数：{{user.followers_count}}<br>
    </div>
  </div>

  </section>
</template>



<script>
export default{
    props:[
      'users_results', //利用中のユーザーがフォローしていないアカウントの情報。Twitter認証していればこの情報を出します。
      'follow_users', //ランダムにDBから取得したユーザー情報
      'autofollow_ajax',//個別フォローするurlへのポストの時のurl
      'autofollowall_ajax',//url情報。autofollow/allです。
      'autofollow_check' //db上から取得したautofollowの状態。1ならばtrue、つまり自動フォロー中。
    ],
    data:function(){
      return{
        el: '#twitter',
        reset_ok:true,
        ongoing:"", //自動フォローを実施している状態。trueであれば自動フォローON。
        users:this.users_results, //users_resultsをusersに詰め込んでおく。
        auto_status:this.autofollow_check
      }
    },
    mounted(){
      //mountedでページアクセス時に自動フォローを実施しているか判定。1なら自動フォロー中で、ongoingをtrue。
      //ongoingがtrueの場合、「自動フォロー実施中です」という表示が出る。
      console.log(this.autofollow_check);
      if(this.autofollow_check == 1){
        this.ongoing = true;
      }else{
        this.ongoing = false;
      }
    },
    methods:{
      //個別フォローのメソッド。
      //Autofollowコントローラーのfollowメソッドへフォロー対象のユーザーデータとともにajaxでアクセス。
      //アクセス先でそのユーザーデータを元にフォローし、「フォローしました」アラートと共に、
      //対象のユーザーデータを「users」から削除（画面から非表示にする）
      follow:function(user,index){
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
      //自動フォローを切り替えた際にボタンの表示、「自動フォロー実施中です」の表示非表示を切り替えるメソッド
      checkOngoing:function(){
        console.log("checkOngoingを呼び出します");
        if(this.autofollow_check == 1 || true){
          this.ongoing = true;
        }else{
          this.ongoing = false;
        }
        console.log("this.ongoingの値です");
        console.log(this.ongoing);
      },
      //まとめてフォロー（自動フォローのONOFFを切り替えるメソッド）
      autofollowStart:function(){
        let self = this;
        let url = this.autofollowall_ajax; //ajax先のurl
        let auto_status = this.auto_status;
        //今現在のDB上のautofollowの状態が1の場合オートフォローの状態を0にする
        if(self.auto_status == 1){
          //console.log(self.auto_status);
          //console.log("今現在の値です");
          this.ongoing = true;
          self.auto_status = 0;
        }else{
          //console.log(self.auto_status);
          //console.log("今現在の値です");
          this.ongoing = false;
          self.auto_status  = 1; //今現在のフォローの状態が1ではない場合、フォローの状態を1にする
        }
          let request = self.auto_status;
          //console.log("切り替え後のauto_statusの状態です");
          //console.log(request);
          axios.post(url, {
          request}).then((res)=>{
          alert('まとめてフォローの設定を切り替えました。ページを再読み込みします。');
          location.reload();
          }).catch( error => { console.log(error); });
      }
    },
    computed:{
     //個別フォローをした際にfollowingがfalseのユーザーを表示から削除する算出プロパティ
     nofollow:function(){
     return this.users.filter(function(user){
     return user.following == false;
     });
     }
   }
}
</script>

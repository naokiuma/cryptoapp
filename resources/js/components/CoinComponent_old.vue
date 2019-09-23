






<template>


<section>

<section class="p-coinranking__top">
  <h2>ツイート数ランキングを見る</h2>
  <p>ツイート数の多いランキング順に並べました。</p>
</section>

  <section class="l-side">
    <div class="p-sidebtn__container">
      <button class="p-sidebtn" v-on:click="sortCoins" type="button" name="button">過去1時間のランキング</button>
      <button class="p-sidebtn"  type="button" name="button">過去１日のランキング</button>
      <button class="p-sidebtn"  type="button" name="button">過去1週間のランキング</button>

        <div class="p-sidebtn__coin__container">
          <div v-for="pcoin in coins" v-bind:key="pcoin.id">
           <label><input type="checkbox" value="all" v-model="choised">{{pcoin.name}}</label>
          </div>
        </div>
    </div>



  </section>

  <section class="p-coinranking__container">
      <div class="p-coinranking__table" v-if="hour_show">
        <h3>1時間のデータ：<span> 更新日時</span></h3>

        <table>
        <th>ランキング</th><th>コイン名</th><th>ツイート数</th>
        <tr v-for="(coin,i) in sortCoinsByHour"
            v-bind:key="coin.id">

            <td>{{ i + 1 }}</td><td>{{coin.name}}</td> <td>{{coin.hour}}</td>
        </tr>
        </table>
      </div>

    <div v-show="day_show"> 1日のデータ</div>
    <div v-show="week_show"> 1weekのデータ</div>

    ここです。
    <div v-for="pcoin in filteredCoins"
         class="p-coinranking__each">
        <h2>{{pcoin}}</h2>
    </div>
  </section>

</section>


</template>



<script>
    export default {
        data:function(){
            return{
            coins:[],
            showCoins:[],
            choised:[],
            hour_show:false,
            day_show:false,
            week_show:false,
            isClicked:null
            }
        },
        mounted(){
        this.sortCoins();
            var self = this;
            var url = '/ajax/coin';
            axios.get(url).then(function(response){
              self.coins = response.data;
              console.log(self.coins);
            });
        },
        computed:{
          sortCoinsByHour:function(){
            if(this.hour_show){
            return this.coins.slice().sort((a,b) => {
              return (b.hour < a.hour) ? -1 : (b.hour > a.hour) ? 1 : 0;
                });
                }
            },

            filteredCoins: function(){
              var coins = [];
              for(var i in this.coins) {
              var coin = this.coins[i];
              if(coin.name.indexOf(this.choised) !== -1) {
              coins.push(coin);
                  }
                }
              return coins;

              }
          },

        methods:{
          sortCoins:function(){
            console.log("メソッド実行。");
            this.hour_show = true;
            },
          pushCoin(pcoin){
          console.log(this.BTC);
              console.log(pcoin);
              console.log(pcoin.pname);
              console.log(pcoin.id);

          if(this.showCoins.indexOf(pcoin.pname) == -1){
          console.log("未登録です、pushcoin実行します。");
            this.showCoins.push(pcoin);
            this.isClicked = pcoin.id;
            this.hour_show = false;
            this.day_show = false;
            this.week_show = false;
            console.log(this.showCoins);

            }else{
                console.log("pushcoin要素削除");
                this.showCoins = [];
              }
          }

        }

    }
</script>

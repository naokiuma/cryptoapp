<template>


<section>

  <section class="l-side">
    <div class="p-sidebtn__container">
      <button class="p-sidebtn" v-on:click="showHour" type="button" name="button">過去1時間</button>
      <button class="p-sidebtn" v-on:click="showDay" type="button" name="button">過去１日</button>
      <button class="p-sidebtn" v-on:click="showWeek" type="button" name="button">過去1週間</button>

        <div class="p-sidebtn__coin__container">
          <div v-for="pcoin in coins"
                v-bind:key="pcoin.id">
            <button class="p-sidebtn__coin" v-on:click="pushCoin(pcoin)"
            v-bind:class="[isClicked == pcoin.id ? 'btn_active' : '']" type="button" name="button">
            {{pcoin.name}}
            </button>
          </div>
          <button class="p-sidebtn__highlight" v-on:click="resetCoin()">
          リセット</button>

        </div>

    </div>
  </section>



  <section class="p-coinranking__container">

        <div class="p-coinranking__table" v-if="hour_show">
          <h3>過去1時間 <span> 更新日時：{{hour}}</span></h3>

          <table>
          <th>順位</th><th>コイン名</th><th>ツイート数</th>
          <tr v-for="(coin,i) in sortCoinsByHour"
              v-bind:key="coin.id">
              <td>{{ i + 1 }}</td><td><a :href="'https://twitter.com/search?q=' + coin.name + '&src=typed_query'" target="_blank">{{coin.name}}</a></td> <td>{{coin.hour}}</td>
          </tr>
          </table>
        </div>


        <div class="p-coinranking__table" v-if="day_show">
          <h3>過去1日 <span> 更新日時：{{day}}</span></h3>

          <table>
          <th>順位</th><th>コイン名</th><th>ツイート数</th>
          <tr v-for="(coin,i) in sortCoinsByDay"
              v-bind:key="coin.id">

              <td>{{ i + 1 }}</td><td><a :href="'https://twitter.com/search?q=' + coin.name + '&src=typed_query'" target="_blank">{{coin.name}}</a></td> <td>{{coin.day}}</td>
          </tr>
          </table>
        </div>


        <div class="p-coinranking__table" v-if="week_show">
          <h3>過去1週間 <span> 更新日時：{{week}}</span></h3>

          <table>
          <th>順位</th><th>コイン名</th><th>ツイート数</th>
          <tr v-for="(coin,i) in sortCoinsByWeek"
              v-bind:key="coin.id">

              <td>{{ i + 1 }}</td><td><a :href="'https://twitter.com/search?q=' + coin.name + '&src=typed_query'" target="_blank">{{coin.name}}</a></td> <td>{{coin.week}}</td>
          </tr>
          </table>
        </div>

        <div v-for="pcoin in showCoins"class="p-coinranking__table">
        <h3><a :href="'https://twitter.com/search?q=' + pcoin.name + '&src=typed_query'" target="_blank">{{ pcoin.name }}</a></h3>

           <table>
          <th>hour</th><th>day</th><th>week</th><th>最高取引価格/24h</th><th>最安取引価格/24h</th>
           <tr>
          <td>{{pcoin.hour}}</td><td>{{pcoin.day}}</td><td >{{pcoin.week}}</td>
          <td>{{pcoin.high}}</td>
          <td>{{pcoin.low}}</td>
           </tr>
           </table>
       </div>


  </section>

</section>
</template>



<script>
    export default {
        props:[
        'coin_ajax',
        'hour',
        'day',
        'week'
        ],

        data:function(){
            return{
            coins:[],
            showCoins:[],
            exitCoins:[],
            coinalldate:this.coinupdatedate,
            link_before:'https://twitter.com/search?q=',
            link_after:'&src=typed_query',
            hour_show:false,
            day_show:false,
            week_show:false,
            isClicked:null,
            flash_message:false
            }
        },
        mounted(){
        console.log(this.hour);
        console.log(this.week);
        this.showHour();
            var self = this;
            var url = this.coin_ajax;
            axios.get(url).then(function(response){
              self.coins = response.data;
              console.log(self.coins);
            });
        },
        computed:{
          sortCoinsByHour:function(){
            if(this.hour_show){

            var arr =this.coins;
            return arr.slice().sort(function(a,b){
              return b.hour - a.hour;
              });
                }
              },
          sortCoinsByDay:function(){
            if(this.day_show){

            var arr =this.coins;
            return arr.slice().sort(function(a,b){
              return b.day - a.day;
              });
                }
              },
          sortCoinsByWeek:function(){
            if(this.week_show){
            console.log("一週間でソートします。");
            var arr =this.coins;
            return arr.slice().sort(function(a,b){
              return b.week - a.week;
              });
                }
              }

        },

        methods:{
          showHour:function(){
            console.log("1時間のデータ表示。");
            this.hour_show = true;
            this.showCoins = [];
            this.exitCoins = [];
            this.day_show = false;
            this.week_show = false;
            },
          showDay:function(){
            console.log("1日のデータ表示。");
            this.day_show = true;
            this.showCoins = [];
            this.exitCoins = [];
            this.hour_show = false;
            this.week_show = false;
            },
          showWeek:function(){
            console.log("1週間のデータ表示。");
            this.week_show = true;
            this.showCoins = [];
            this.exitCoins = [];
            this.hour_show = false;
            this.day_show = false;
              },

          pushCoin(pcoin){
              if (this.exitCoins.indexOf(pcoin.name) == -1){
                console.log("未登録です、pushcoin実行します。");
                this.showCoins.push(pcoin);
                this.isClicked = pcoin.id;
                this.exitCoins.push(pcoin.name);
                console.log("exitcoinです");
                console.log(this.exitCoins);
                this.hour_show = false;
                this.day_show = false;
                this.week_show = false;
                }else{
                    console.log("すでに登録済みです。");
                    this.flash_message = true;

                }
              },
          resetCoin(){
            console.log("削除します。");
              this.showCoins = [];
              this.exitCoins = [];
              this.hour_show = true;

          }

        }

    }
</script>

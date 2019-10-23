<template>
<section>

  <section class="l-side">

  <!--過去のツイート数集計を表示させるラジオボタン。-->
    <div class="p-sidebtn__container">
      <label><input class="p-sidebtn" v-on:click="showHour" type="radio" name="tweet">過去1時間</label>
      <label><input class="p-sidebtn" v-on:click="showDay" type="radio" name="tweet">過去1日</label>
      <label><input class="p-sidebtn" v-on:click="showWeek" type="radio" name="tweet">過去1週間</label>
    </div>


  <!--各種コインの情報を表示させるチェックボックス。-->
    <div class="p-sidebtn__coin__container">
      <div class="p-sidebtn__coin" v-for="pcoin in coins" v-bind:key="pcoin.id">
        <label><input class="p-sidebtn__coin__input" v-on:click="pushCoin(pcoin)" type="checkbox" name="button">
        <span class="p-sidebtn__coin__checkparts">{{pcoin.name}}</span>
        </label>
      </div>
      <!--リセット。初期化する-->
      <button class="p-sidebtn__highlight" v-on:click="resetCoin()">リセット</button>
    </div>

  </section>



  <section class="p-coinranking__container">

    <!--過去1時間のツイート数ランキングを表示-->
    <div class="p-coinranking__table" v-if="hour_show">
      <h3>過去1時間のツイート数 <span> 更新日時：{{hour}}</span></h3>
      <table>
        <th>順位</th><th>コイン名</th><th>ツイート数</th>
        <tr v-for="(coin,i) in sortCoinsByHour" v-bind:key="coin.id">
        <td>{{ i + 1 }}</td><td><a :href="'https://twitter.com/search?q=' + coin.name + '&src=typed_query'" target="_blank">{{coin.name}}</a></td> <td>{{coin.hour}}</td>
        </tr>
      </table>
    </div>

    <!--過去1日のツイート数ランキングを表示-->
    <div class="p-coinranking__table" v-if="day_show">
      <h3>過去1日のツイート数 <span> 更新日時：{{day}}</span></h3>
      <table>
        <th>順位</th><th>コイン名</th><th>ツイート数</th>
        <tr v-for="(coin,i) in sortCoinsByDay" v-bind:key="coin.id">
        <td>{{ i + 1 }}</td><td><a :href="'https://twitter.com/search?q=' + coin.name + '&src=typed_query'" target="_blank">{{coin.name}}</a></td> <td>{{coin.day}}</td>
        </tr>
      </table>
    </div>

    <!--過去1週間のツイート数ランキングを表示-->
    <div class="p-coinranking__table" v-if="week_show">
      <h3>過去1週間のツイート数 <span> 更新日時：{{week}}</span></h3>
      <table>
        <th>順位</th><th>コイン名</th><th>ツイート数</th>
        <tr v-for="(coin,i) in sortCoinsByWeek" v-bind:key="coin.id">
        <td>{{ i + 1 }}</td><td><a :href="'https://twitter.com/search?q=' + coin.name + '&src=typed_query'" target="_blank">{{coin.name}}</a></td> <td>{{coin.week}}</td>
        </tr>
      </table>
    </div>

    <div v-for="pcoin in showCoins"class="p-coinranking__table">
      <h3><a :href="'https://twitter.com/search?q=' + pcoin.name + '&src=typed_query'" target="_blank">{{ pcoin.name }}</a></h3>
      <h4>ツイート数集計</h4>
      <table>
        <th>過去1時間</th><th>過去1日</th><th>過去1日</th>
        <tr>
        <td>{{pcoin.hour}}</td><td>{{pcoin.day}}</td><td >{{pcoin.week}}</td>
        </tr>
      </table>
      <h4>過去24時間の取引価格</h4>
      <table>
        <th>最高取引価格</th><th>最安取引価格</th>
        <tr>
        <td>{{pcoin.high}}</td><td>{{pcoin.low}}</td>
        </tr>
      </table>
    </div>

  </section>

</section>
</template>



<script>
    export default {
        props:[ //それぞれcoinのindexページから取得
        'coin_ajax', //coinのデータ。
        'hour', //過去1時間のデータ。
        'day', //過去1日のデータ
        'week' //過去1習慣のデータ
        ],

        data:function(){
            return{
            coins:[],
            showCoins:[], //コインのツイート数、取引額の見た目上のデータをここに詰め込む
            exitCoins:[], //コインのツイート数、取引額の実際のデータをここに詰め込む
            coinalldate:this.coinupdatedate,
            link_before:'https://twitter.com/search?q=', //ツイッター上にリンクするための情報
            link_after:'&src=typed_query',               //同上
            hour_show:false,
            day_show:false,
            week_show:false,
            }
        },
        mounted(){
        this.showHour();
            let self = this;
            let url = this.coin_ajax;
            axios.get(url).then(function(response){
              self.coins = response.data;
            });
        },
        computed:{
          sortCoinsByHour:function(){
            if(this.hour_show){

            let arr =this.coins;
            return arr.slice().sort(function(a,b){
              return b.hour - a.hour;
              });

            }
          },
          sortCoinsByDay:function(){
            if(this.day_show){

            let arr =this.coins;
            return arr.slice().sort(function(a,b){
              return b.day - a.day;
              });

            }
          },
          sortCoinsByWeek:function(){
            if(this.week_show){
            let arr =this.coins;
            return arr.slice().sort(function(a,b){
              return b.week - a.week;
              });

            }
          }
        },

        methods:{
          showHour:function(){
            this.hour_show = true;
            //this.showCoins = [];
            //this.exitCoins = [];
            this.resetCheckbox();
            this.day_show = false;
            this.week_show = false;
            },
          showDay:function(){
            this.day_show = true;
            //this.showCoins = [];
            //this.exitCoins = [];
            this.resetCheckbox();
            this.hour_show = false;
            this.week_show = false;
            },
          showWeek:function(){
            this.week_show = true;
            //this.showCoins = [];
            //this.exitCoins = [];
            this.resetCheckbox();
            this.hour_show = false;
            this.day_show = false;
              },

          pushCoin(pcoin){ //exitCoinsは表示上のコインではなく、データ上登録されているcoinデータ。
              if (this.exitCoins.indexOf(pcoin.name) == -1){ //exitCoinにpcoin.nameがなければ追加する
              console.log("コインがないです");
              console.log(this.exitCoins);
                this.showCoins.push(pcoin);
                this.exitCoins.push(pcoin.name);
                console.log(this.exitCoins);
                this.hour_show = false;
                this.day_show = false;
                this.week_show = false;
                }else{
                console.log("コインがあるぽい");
                console.log(this.exitCoins);
                    //this.showCoins.pop(pcoin);//popだと末尾を削除するからダメ。
                    //this.exitCoins.pop(pcoin.name);
                    this.exitCoins = this.exitCoins.filter(n => n !== pcoin.name);
                    this.showCoins = this.showCoins.filter(n => n !== pcoin);
                console.log(this.exitCoins);


                }
              },
          //表示内容を初期化するメソッド。
          resetCoin(){
              this.showCoins = [];
              this.exitCoins = [];
              this.hour_show = true;
              this.day_show = false;
              this.week_show = false;
              this.resetCheckbox();
          },
          //チェックボックスのチェックをリセットするメソッド。
          //期間集計を表示するときにも使うため「resetCoin」とは分けています。
          resetCheckbox(){
          let checkboxs = document.getElementsByClassName( "p-sidebtn__coin__input" );
          for (var i=0; i<checkboxs.length; i++){
               checkboxs[i].checked = false;
               }

          }

        }

    }
</script>

<template>
<section>

  <section class="l-side">

    <!--過去のツイート数集計を表示させるラジオボタン。-->
    <!--それぞれのボタンをクリックすると、日、時間、週間のツイート数を表示させるメソッドが発火。-->
    <div class="p-sidebtn__container">
      <label v-bind:class='{btn_active:hour_show}'><input class="p-sidebtn" v-on:click="showHour" type="radio" name="tweet">過去1時間</label>
      <label v-bind:class='{btn_active:day_show}'><input class="p-sidebtn" v-on:click="showDay" type="radio" name="tweet">過去1日</label>
      <label v-bind:class='{btn_active:week_show}'><input class="p-sidebtn" v-on:click="showWeek" type="radio" name="tweet">過去1週間</label>
    </div>

    <!--コイン一覧のチェックボックスの表示非表示とリセットボタン。-->
    <!--スマホではコイン情報（行21~29）をデフォルトで表示すると、実際のデータの表示部分がかなり下になるため、表示非表示を切り替え可能にしました。-->
    <div class="p-sidebtn__coinshow">
      <button class="p-sidebtn__toshowcoin" v-on:click ="coinbuttonShow()">コイン情報を調べる</button>
      <button class="p-sidebtn__highlight" v-on:click="resetCoin()">リセット</button>
    </div>

    <!--各種コインの情報を表示させるチェックボックス。check_showがtrueになれば表示される。-->
    <div class="p-sidebtn__coin__container" v-if="check_show">
      <div class="p-sidebtn__coin" v-for="pcoin in coins" v-bind:key="pcoin.id">
        <label><input class="p-sidebtn__coin__input" v-on:click="pushCoin(pcoin)" type="checkbox" name="button">
        <span class="p-sidebtn__coin__checkparts">{{pcoin.name}}</span>
        </label>
      </div>
    </div>
  </section>

  <section class="p-coinranking__container">
    <!--過去1時間のツイート数ランキングを表示-->
    <!--hour_showがtrueになると表示。sortCoinsByHourをv-forでループ表示。-->
    <div class="p-coinranking__table" v-if="hour_show">
      <h3>過去1時間のツイート数 <span> 更新日時：{{hour}}</span></h3>
      <table>
        <th>順位</th><th>コイン名</th><th>ツイート</th>
        <tr v-for="(coin,i) in sortCoinsByHour" v-bind:key="coin.id">
        <td>{{ i + 1 }}</td><td><a :href="'https://twitter.com/search?q=' + coin.name + '&src=typed_query'" target="_blank">{{coin.name}}</a></td> <td>{{coin.hour}}</td>
        </tr>
      </table>
    </div>

    <!--過去1日のツイート数ランキングを表示-->
    <!--day_showがtrueになると表示。sortCoinsByDayをv-forでループ表示。-->
    <div class="p-coinranking__table" v-if="day_show">
      <h3>過去1日のツイート数 <span> 更新日時：{{day}}</span></h3>
      <table>
        <th>順位</th><th>コイン名</th><th>ツイート</th>
        <tr v-for="(coin,i) in sortCoinsByDay" v-bind:key="coin.id">
        <td>{{ i + 1 }}</td><td><a :href="'https://twitter.com/search?q=' + coin.name + '&src=typed_query'" target="_blank">{{coin.name}}</a></td> <td>{{coin.day}}</td>
        </tr>
      </table>
    </div>

    <!--過去1週間のツイート数ランキングを表示-->
    <!--week_showがtrueになると表示。sortCoinsByWeekをv-forでループ表示。-->
    <div class="p-coinranking__table" v-if="week_show">
      <h3>過去1週間のツイート数 <span> 更新日時：{{week}}</span></h3>
      <table>
        <th>順位</th><th>コイン名</th><th>ツイート</th>
        <tr v-for="(coin,i) in sortCoinsByWeek" v-bind:key="coin.id">
        <td>{{ i + 1 }}</td><td><a :href="'https://twitter.com/search?q=' + coin.name + '&src=typed_query'" target="_blank">{{coin.name}}</a></td> <td>{{coin.week}}</td>
        </tr>
      </table>
    </div>

    <!--各種コインごとの情報（showCoins）をv-forでループ表示。-->
    <div v-for="pcoin in showCoins" class="p-coinranking__table">
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
    'coin_ajax', //coinのデータを取得するためのajaxに使うURL。
    'hour', //過去1時間のデータ。
    'day', //過去1日のデータ
    'week' //過去1習慣のデータ
    ],
    data:function(){
      return{
        coins:[],
        showCoins:[], //コインのツイート数、取引額の見た目上のデータをここに詰め込む
        exitCoins:[], //コインのツイート数、取引額の実際のデータをここに詰め込む
        link_before:'https://twitter.com/search?q=', //ツイッター上にリンクするためのURL情報前半
        link_after:'&src=typed_query',               //同上
        hour_show:false, //trueになれば1時間ごとのツイート数を表示
        day_show:false, //trueになれば1日ごとのツイート数を表示
        week_show:false, //trueになれば1週間ごとのツイート数を表示
        check_show:false, //trueになればコインボタンの表示を行う。
      }
    },
    //ページ表示の時点では1時間ごとの表示を行う。
    mounted(){
      this.showHour();
      let self = this;
      let url = this.coin_ajax;
      axios.get(url).then(function(response){
        self.coins = response.data;
        });
    },
    computed:{
      //時間ごとのツイート数を多い順に並び替える算出プロパティ。hour_showは。
      sortCoinsByHour:function(){
        if(this.hour_show){
          let arr =this.coins;
          return arr.slice().sort(function(a,b){
          return b.hour - a.hour;
          });
        }
      },
      //1日ごとのツイート数を多い順に並び替える。
      sortCoinsByDay:function(){
        if(this.day_show){
          let arr =this.coins;
          return arr.slice().sort(function(a,b){
          return b.day - a.day;
          });
        }
      },
      //1週間ごとのツイート数を多い順に並び替える。
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
      //1時間ごとのコインのツイート数を出すメソッド
      showHour:function(){
      this.hour_show = true;
      console.log(this.hour_show);
      this.showCoins = [];
      this.exitCoins = [];
      this.resetCheckbox();
      this.day_show = false;
      this.week_show = false;
      },
      //1日ごとのコインのツイート数を出すメソッド
      showDay:function(){
        this.day_show = true;
        console.log(this.day_show);
        this.showCoins = [];
        this.exitCoins = [];
        this.resetCheckbox();
        this.hour_show = false;
        this.week_show = false;
      },
      //1週間ごとのコインのツイート数を出すメソッド
      showWeek:function(){
        this.week_show = true;
        console.log(this.week_show);
        this.showCoins = [];
        this.exitCoins = [];
        this.resetCheckbox();
        this.hour_show = false;
        this.day_show = false;
      },
      //exitCoinsは表示上のコインではなく、データ上登録されているcoinデータ。
      pushCoin(pcoin){
        //exitCoinにpcoin.nameがなければ追加する
        if (this.exitCoins.indexOf(pcoin.name) == -1){
          this.showCoins.push(pcoin);
          this.exitCoins.push(pcoin.name);
          console.log(this.exitCoins);
          this.hour_show = false;
          this.day_show = false;
          this.week_show = false;
        }else{
          console.log(this.exitCoins);
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
      },
      //コインの表示をするためのボックスを出し入れするメソッド。
      coinbuttonShow(){
       console.log("スタート");
       this.check_show = !this.check_show;
      }
    }
  }
</script>

<template lang="jade">
.buy-wrapper(v-if="detail")
  .sell-photo(:style="{background: '#fff url('+ '/photo/' + detail.imgName +') center center no-repeat', backgroundSize: '100% auto'}", v-if="detail.imgName")
  .sell-photo(:style="{background: '#fff url(../../static/image/no_picture.jpg) center center no-repeat', backgroundSize: '100% 100%'}", v-if="!detail.imgName")
  h4.buy-title {{detail.title}}
  p.buy-detail {{detail.detail}}
  h3.buy-money
    span.money {{'￥' + detail.money}}
    span.little(v-if="detail.isLittle === 1") 可小刀
  .options
    span.sell-back(@click="sellBack", v-if="detail.user_type === 1") 不卖了
    span.sell-out(@click="sellOut", v-if="detail.user_type === 1") 已卖掉
    span.shut-down(@click="shutDown", v-if="detail.isAdmin === 1 && detail.user_type !== 1") 查封
    span.want-buy(@click="buyNow", v-if="detail.user_type===2") 我要买
</template>

<script>
// 引入插件
let PubSell = require('../plugins/SellTip/SellTip.js')
let Vue = require('vue')
Vue.use(PubSell)
// let formatDate = require('../../static/js/tool.js').formatDate
let AJAX = require('../../static/js/ajax.js')
export default {
  data () {
    return {
    }
  },
  props: {
    detail: Object,
    pid: String,
  },
  mounted() {
  },
  methods: {
    buyNow: function () {
      if(!this.checkStatus()) return
      Vue.$pubtip({
        type: 5,
        msg: '复制卖家的微信号，与TA联系',
        sureText: '一键复制',
        pub_user: this.detail.pub_wxid
      })
      this.$http.post(AJAX.buy, {title: this.detail.title, pid: this.pid}).then(function (res) {
        if (typeof res.data === 'string') {
          res.data = JSON.parse(res.data)
        }
        if (res.data.status === 1) {
        } else if (res.data.status === 500) {
          window.location = AJAX.notLogin + 'help'
        }
      })
    },
    sellOut: function () {
      if(!this.checkStatus()) return
      Vue.$pubtip({
        type: 0,
        msg: '你的宝贝已经转手了么?',
        cancelText: '没呢',
        sureText: '是的',
        success: hasBeenToken
      })
      function hasBeenToken () {
        this.$http.post(AJAX.sellout, {pid: window.pid, status: 1}).then(function (res) {
          if (typeof res.data === 'string') {
            res.data = JSON.parse(res.data)
          }
          if (res.data.status === 1) {
            Vue.$pubtip({
              type: 0,
              msg: '你的宝贝已下架',
              sureText: '朕知道了'
            })
          } else if (res.data.status === 500) {
            window.location = AJAX.notLogin + 'buy'
          } else {
            Vue.$pubtip({
              type: 0,
              msg: '商品下架失败，请重试',
              sureText: '朕知道了'
            })
          }
        })
      }
    },
    sellBack: function () {
      if(!this.checkStatus()) return
      Vue.$pubtip({
        type: 0,
        msg: '不想卖了么？',
        cancelText: '忍痛卖了',
        sureText: '是的！',
        success: cancelThisTask
      })
      function cancelThisTask () {
        this.$http.post(AJAX.sellback, {pid: window.pid, status: 2}).then(function (res) {
          if (typeof res.data === 'string') {
            res.data = JSON.parse(res.data)
          }
          if (res.data.status === 1) {
            Vue.$pubtip({
              type: 1,
              msg: '商品已下架',
              sureText: '朕知道了'
            })
          } else if (res.data.status === 500) {
            window.location = AJAX.notLogin + 'buy'
          } else {
            Vue.$pubtip({
              type: 1,
              msg: '下架失败，请重试',
              sureText: '朕知道了'
            })
          }
        })
      }
    },
    shutDown: function () {
      if(!this.checkStatus()) return
      Vue.$pubtip({
        type: 0,
        msg: '查封此商品',
        cancelText: '取消',
        sureText: '确定',
        success: cancelThisTask
      })
      function cancelThisTask () {
        this.$http.post(AJAX.shutdown, {pid: window.pid, status: 3}).then(function (res) {
          if (typeof res.data === 'string') {
            res.data = JSON.parse(res.data)
          }
          if (res.data.status === 1) {
            Vue.$pubtip({
              type: 1,
              msg: '查封成功',
              sureText: '朕知道了'
            })
          } else if (res.data.status === 500) {
            window.location = AJAX.notLogin + 'buy'
          } else {
            Vue.$pubtip({
              type: 1,
              msg: '查封失败，请重试',
              sureText: '朕知道了'
            })
          }
        })
      }
    },
    checkStatus: function () {
      if (this.detail.status === 1) {
        Vue.$pubtip({
          type: 1,
          msg: '商品已卖出，去看看别的吧~',
          sureText: '朕知道了'
        })
        return false
      } else if (this.detail.status === 2) {
        Vue.$pubtip({
          type: 1,
          msg: '卖家已下架商品喔，去看看别的吧~',
          sureText: '朕知道了'
        })
        return false
      } else if (this.detail.status === 3) {
        Vue.$pubtip({
          type: 1,
          msg: '该商品涉嫌违规，已被查封',
          sureText: '朕知道了'
        })
        return false
      } else {
        return true
      }
    }
  }
}

const GetQueryString = (name) => {
  var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
  var r = window.location.search.substr(1).match(reg);
  if(r!=null)return  unescape(r[2]); return null;
}
</script>

<style lang="sass" scoped>
.buy-wrapper
  padding-bottom: .62rem
.sell-photo
  width: 79.3%
  height: 5.23rem
  margin: 1.15rem auto 0
  border-radius: .1rem
.buy-title
  font-size: .46rem
  color: #446996
  margin: .25rem auto 0
  width: 79.3%
.buy-detail
  font-size: .32rem
  color: #446996
  width: 79.3%
  margin: .2rem auto 0
.buy-money
  width: 79.3%
  margin: .3rem auto 0
  display: flex
  align-items: flex-end
  .money
    font-size: .6rem
    color: #33bda6
    margin-right: .2rem
    line-height: 1
  .little
    color: #446996
    font-size: .3rem
    padding-bottom: .03rem
.options
  margin: .3rem auto 0
  display: flex
  justify-content: space-around
  span
    display: inline-block
    width: 2.9rem
    font-size: .4rem
    line-height: 1rem
    height: 1rem
    flex: 0 1 auto
    text-align: center
    border-radius: .5rem
  span.sell-back
    background-color: #fff
    color: #f1787d
  span.sell-out
    background-color: #f1787d
    color: #ffffff
  span.shut-down
    background-color: #f1787d
    color: #ffffff
  span.want-buy
    background-color: #f1787d
    color: #ffffff
</style>

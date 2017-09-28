<template lang="jade">
ul.market-container(v-if="data")
  li(v-for="item in data", :class="{'grey': item.status > 0}", @click="setDetail(item)")
    //- a(:href="item.status === 0 ? 'buy?pid='+item.pid : 'javascript:void(0)'")
    .info
      .status(:class="{'waiting': item.status===0, 'sellout': item.status===1, 'back': item.status===2 || item.status === 3}")
      .text
        h4
          span.title {{item.title}}
          span.money {{"￥" + item.money}}
        p {{item.detail}}
    p.is-little
      span.text() {{item.isLittle ? '可小刀' : ''}}
      span.icon(v-if="item.status===0")
  div.add(@click="addSell")
    //- span +
    img(src="../../static/image/plus.png")
  div.sell-detail(v-show="detail")
    <buy-view :detail="detail" :pid="pid"></buy-view>
</template>

<script>
// let formatDate = require('../../static/js/tool.js').formatDate
let AJAX = require('../../static/js/ajax.js')
// 引入插件
let PubSell = require('../plugins/SellTip/SellTip.js')
let Vue = require('vue')
Vue.use(PubSell)

import BuyView from './buy.vue'
window.isPubing = false
export default {
  data() {
    return {
     data: [],
     detail: null,
     pid: null,
    }
  },
  mounted() {
    this.$http.get(AJAX.market).then(function (res) {
      if (typeof res.data === 'string') {
        res.data = JSON.parse(res.data)
      }
      if (res.data.status === 1) {
        this.data = res.data.data
        if (!this.data) {
          this.data = []
        }
      }
    })
    var that = this
    window.onpopstate = function (e) {
      if (that.detail) {
        e.preventDefault()
        that.detail = null
      }
    }
  },
  components: {
    'BuyView': BuyView
  },
  methods: {
    addSell () {
      window.location.href = 'pubsell'
    },
    setDetail (item) {
      window.history.pushState({}, null, 'buy')
      window.pid = item.pid
      this.$http.get(AJAX.malldetail + "?pid=" + item.pid).then(function (res) {
        if (typeof res.data === 'string') {
          res.data = JSON.parse(res.data)
        }
        if (res.data.status === 1) {
          this.detail = res.data.data
          this.pid = item.pid
        } else if (res.data.status === 500) {
          window.location = AJAX.notLogin + 'market'
        } else if (res.data.status === 202) {
          Vue.$pubtip({
            type: 1,
            msg: '任务不存在喔',
            sureText: '朕知道了'
          })
        } else if (res.data.status === -1){
          Vue.$pubtip({
            type: 1,
            msg: '后台炸了'
          })
        }
      })
    }
  }
}
</script>

<style lang="sass" scoped>
.sell-detail
  width: 100%
  height: 100%
  position: fixed
  top: 0
  left: 0
  z-index: 50
  background: #e9efe3
  overflow: scroll
.market-container
  overflow-y: scroll
  padding: .85rem .35rem .6rem
  & > a
    display: block
  li
    background-color: #fff
    border-radius: .1rem
    margin-top: .3rem
    box-shadow: .02rem .03rem .05rem .05rem rgba(100,100,100,.15)
    padding: .27rem .3rem
    &.grey
      background-color: #b2b2b2
  .add
    position: fixed
    width: 1.1rem
    height: 1.1rem
    border-radius: 50%
    right: .5rem
    bottom: .5rem
    background-color: #f1787d
    background-size: .2rem auto
    color: #ffffff
    font-size: 1rem
    line-height: 1.1rem
    text-align: center
    box-shadow: 0 6px 10px 0 rgba(0,0,0,0.3)
    cursor: pointer
    span
      position: absolute
      top: 50%
      left: 50%
      transform: translate(-50%, -50%)
    img
      position: absolute
      top: 50%
      left: 50%
      width: .55rem
      height: auto
      transform: translate(-50%, -50%)
.info
  display: flex
  justify-content: space-between
  border-bottom: 1px solid #e5e5e5
  .status
    width: .95rem
    height: .95rem
    border-radius: 50%
    flex: none
    overflow: hidden
    &.waiting
      background: url(/static/image/waiting.png) center center no-repeat
      background-size: 100% 100%
    &.sellout
      background: url(/static/image/sellout.png) center center no-repeat
      background-size: 100% 100%
    &.back
      background: url(/static/image/back.png) center center no-repeat
      background-size: 100% 100%
  .text
    flex: auto
    width: 4.5rem
    margin-left: .2rem
    h4
      display: flex
      font-size: .34rem
      color: #000000
      justify-content: space-between
      overflow: hidden
      .title
        flex: auto
        white-space: nowrap
        overflow: hidden
        text-overflow: ellipsis
        width: 75%
      .money
        flex: auto
        color: #33bda6
    p
      white-space: nowrap
      overflow: hidden
      text-overflow: ellipsis
      font-size: .26rem
      line-height: 2
.is-little
  margin-top: .05rem
  font-size: .26rem
  color: #000000
  display: flex
  justify-content: space-between
  align-items: center
  margin-top: .1rem
  .text
    flex: auto
    line-height: 1
  .icon
    display: inline-block
    width: .21rem
    height: .21rem
    flex: none
    vertical-align: middle
    background: url(/static/image/tosee.png) 100% center no-repeat
    background-size: .1rem auto

</style>

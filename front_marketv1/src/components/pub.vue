<template lang="jade">
.pubtask-wrapper
  form.task-info
    .row
      label 任务名称
      .form-control
        input(type="text", maxlength="24", v-model="title")
    .row
      label 执行时间
      .form-control
        input(type="text", class="end-date", maxlength='20',placeholder="明晚之前", v-model="time")
    .row
      label 详情要求
      .form-control
        textarea(placeholder="在这里说一说任务的具体内容，和对执行者的要求。清楚的描述更容易获得帮助哦！", v-model="detail")
    .row
      label 悬赏金额
      .form-control
        input(type="number", max="10000", min="0", step="10", v-model="money", placeholder="我愿意支付多少报酬给帮助者")
    .button-row
      span.button-submit(@click="publishTask") 发布任务
</template>

<script>
let TipPlugin = require('../plugins/Tip/Tip.js')
var Vue = require('vue')
Vue.use(TipPlugin)
// let formatDate = require('../../static/js/tool.js').formatDate
let AJAX = require('../../static/js/ajax.js')
window.isPubing = false
export default {
  data () {
    return {
      title: null,
      detail: null,
      time: null,
      money: null
    }
  },
  mounted() {
  },
  methods: {
    publishTask: function () {
      var title = this.title;
      var detail = this.detail;
      var time = this.time;
      var money = this.money;
      var pub_wxid = window.pub_wxid 
      if (!title) {
        Vue.$tip({type: 1, msg: '还没有填写任务名称喔~', sureText: '继续填写'})
        return
      }
      if (!detail) {
        Vue.$tip({type: 1, msg: '还没有填写详情要求喔~', sureText: '继续填写'})
        return
      }
      if (!time) {
        Vue.$tip({type: 1, msg: '还没有填写执行时间喔~', sureText: '继续填写'})
        return
      }
      if (!money) {
        Vue.$tip({type: 1, msg: '还没有填写悬赏金额喔~', sureText: '继续填写'})
        return 
      }
      // if (detail && time && money && title && !window.isPubing) {
      //   window.isPubing = true
      //   Vue.$tip({
      //     type: 0,
      //     msg: '确认发布任务吗？',
      //     sureText: '确定',
      //     cancelText: '再想想',
      //     success: postTask
      //   })
      // }
      if (!pub_wxid) {
        Vue.$tip({
          type: 6,
          msg: '留下微信号，等待接单者与您联系',
          sureText: '确定',
          cancelText: '取消',
          pub_user: window.localStorage.getItem('help_wxid'),
          success: getPubUser.bind(this)
        })
        return 
      }
      var that = this
      function getPubUser (e) {
        if (!window.pub_wxid) {
          Vue.$tip({
            type: 1,
            msg: '没有微信号，买家找不到你喔', 
            sureText: '继续填写',
            success: function () {
              Vue.$tip({
                type: 6,
                msg: '留下微信号，等待接单者与您联系',
                sureText: '确定',
                cancelText: '取消',
                success: getPubUser.bind(this)
              })
            }
          }) 
          return
        } else {
          window.localStorage.setItem('help_wxid', window.pub_wxid)
          postTask.call(this)
        }
      }
      function postTask () {
        // 关闭弹窗
        Vue.$tip({
          type: 2,
          msg: null,
          sureText: '朕知道了'
        })
        this.$http.post(AJAX.publishTask, {
          detail: detail,
          end_date: time,
          money: money,
          title: title,
          pub_wxid: window.pub_wxid
        }).then(function (res) {
          if (typeof res.data === 'string') {
            res.data = JSON.parse(res.data)
          }
          window.isPubing = false
          switch (res.data.status) {
            case 1:
              Vue.$tip({
                type: 2,
                msg: '发布成功',
                sureText: '朕知道了',
                success: function () {
                  window.location = AJAX.taskDetailPage + res.data.data.task_id
                }
              })
              break
            case 500:
              window.location = AJAX.notLogin + 'pub'
              break
            case 101:
              Vue.$tip({
                type: 1,
                msg: '缺少必要信息喔',
                sureText: '朕知道了'
              })
              break
            case 203:
              Vue.$tip({
                type: 3,
                msg: '不是好友'
              })
              break
            case 300:
              Vue.$tip({
                type: 1,
                msg: '距离上次发布还不到五分钟喔',
                sureText: '朕知道了'
              })
              break
            case 301:
              Vue.$tip({
                type: 1,
                msg: '每天有五次的任务上限喔，请明天再发吧！',
                sureText: '朕知道了'
              })
              break
            default:
              Vue.$tip({
                type: 1,
                msg: '发布失败，请重新发布',
                sureText: '朕知道了'
              })
              break
          }
        })
      }
    }
  }
}
</script>

<style lang="scss">
@import "../../static/scss/_variables.scss";
.pubtask-wrapper {
  // position: relative;
  padding: .24rem .48rem 0;
  overflow-y: scroll;
}
.row {
  margin-top: .3rem;
  label {
    font-size: .28rem;
    color: $baseTitleColor;
    padding: .14rem .28rem;
    line-height: 1;
    border-top: 1px solid $baseBorderColor;
    border-left: 1px solid $baseBorderColor;
    border-right: 1px solid $baseBorderColor;
    display: inline-block;
  }
  input {
    border: 1px solid $baseBorderColor;
    font-size: .3rem;
    color: $baseTextColor;
    height: .8rem;
    line-height: .8rem;
    text-indent: .28rem;
    outline: none;
    background: $deepBgColor;
    width: 100%;
    box-sizing: border-box;
  }
  textarea {
    height: 2.4rem;
    font-size: .3rem;
    color: $baseTextColor;
    background: $deepBgColor;
    resize: none;
    padding: .38rem .36rem 0 .32rem;
    width: 100%;
    box-sizing: border-box;
    outline: none;
    border: 1px solid $baseBorderColor;
  }
}
.button-row {
  margin-top: .4rem;
  text-align: center;
  .button-submit {
    display: inline-block;
    color: #fff;
    background: #04333b;
    padding: 0 .78rem;
    height: .84rem;
    line-height: .84rem;
    font-size: .32rem;
    border-top: 1px solid #248888;
    border-bottom: 1px solid #248888;
    border-radius: .2rem;
    cursor: pointer;
  }
}
</style>

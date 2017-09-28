<template lang="jade">
.help-wrapper
  h1 {{task.title}}
  p.task-money ￥{{task.money}}
  div.task-detail
    div.top-bottom-line
    p {{task.detail}}
    h5.task-time {{task.time}}
  div.divide-line
    div.top-line
  section.operates(v-if="!isYoursTask && taskStatus === 0 && isAdmin !== 1")
    div.help-now(@click="helpNow()") 立即帮助
  section.operates(v-if="isYoursTask && taskStatus === 0 && isAdmin !== 1")
    div.been-token(@click="beenToken()") 已被接单
    div.cancel-task(@click="cancelTask()") 取消任务
  section.operates(v-if="isAdmin === 1")
    div.been-token(@click="shutdown()") 查封
    div.help-now(@click="helpNow()") 立即帮助
  div.show-mask(v-if="taskStatus !== 0")
    div.been_token
      span(v-if="taskStatus===1") 已被接单
      span(v-if="taskStatus===2") 任务已取消
      span(v-if="taskStatus===3") 任务已下架
</template>

<script>
let TipPlugin = require('../plugins/Tip/Tip.js')
var Vue = require('vue')
Vue.use(TipPlugin)

let getQueryString = require('../../static/js/tool.js').getQueryString
let AJAX = require('../../static/js/ajax.js')
export default {
  data () {
    return {
      task: {
        id: '',
        title: '',
        detail: '',
        time: '',
        money: '',
        pub_user: '',
        pub_wxid: ''
      },
      isYoursTask: false,
      isAdmin: 0,
      isRobotsFriend: false,
      taskStatus: 0
    }
  },
  mounted() {
    this.task.id = window.taskId = getQueryString("task_id")
    this.$http.get(AJAX.checkTask + "?task_id=" + taskId).then(function (res) {
      if (typeof res.data === 'string') {
        res.data = JSON.parse(res.data)
      }
      if (res.data.status === 1) {
        this.task.title = window.taskTitle = res.data.data.title
        this.task.detail = res.data.data.detail
        this.task.money = res.data.data.money
        this.task.time = res.data.data.end_date
        this.task.pub_user = window.pub_user = res.data.data.pub_user
        this.task.pub_wxid = res.data.data.pub_wxid
        this.taskStatus = res.data.data.task_status
        this.isAdmin = res.data.data.isAdmin
        if (res.data.data.user_type === 1) {
          this.isYoursTask = true
          this.isRobotsFriend = true
          return
        }
        switch (res.data.data.isFriend) {
          case 0:
            this.isRobotsFriend = false
            break
          case 1:
            this.isRobotsFriend = true
            break
          case 2:
            this.$http.get(AJAX.checkFriend).then(function (res) {
              if (typeof res.data === 'string') {
                res.data = JSON.parse(res.data)
              }
              this.isRobotsFriend = res.data.data.isFriend
            })
            break
          default:
            this.isRobotsFriend = false
            break
        }
      } else if (res.data.status === 500) {
        window.location = AJAX.notLogin + 'help'
      } else if (res.data.status === 202) {
        Vue.$tip({
          type: 1,
          msg: '任务不存在喔',
          sureText: '朕知道了'
        })
      } else if (res.data.status === -1){
        Vue.$tip({
          type: 1,
          msg: '后台炸了'
        })
      }
    })
  },
  methods: {
    helpNow: function () {
      // if (!this.isYoursTask && !this.isRobotsFriend) {
      //   Vue.$tip({
      //     type: 3,
      //     msg: '不是好友'
      //   })
      // }
      // else {
        // Vue.$tip({
        //   type: 0,
        //   msg: '确认帮助TA完成这个任务？',
        //   cancelText: '再想想',
        //   sureText: '确定',
        //   success: helpNow
        // })
        Vue.$tip({
          type: 7,
          msg: '复制发布者的微信号，与TA联系',
          sureText: '一键复制',
          pub_user: this.task.pub_wxid
        })
        this.$http.post(AJAX.helpNow, {task_id: window.taskId, pub_user: window.pub_user, title: window.taskTitle, wxId: window.wxId}).then(function (res) {
          if (typeof res.data === 'string') {
            res.data = JSON.parse(res.data)
          }
          if (res.data.status === 1) {
          } else if (res.data.status === 500) {
            window.location = AJAX.notLogin + 'help'
          }
        })
      // }
      function helpNow(e) {
        if (!window.wxId) {
          Vue.$tip({
            type: 1,
            msg: '还没有留下联系方式喔~',
            sureText: '继续填写'
          })
          return
        }
        this.$http.post(AJAX.helpNow, {task_id: window.taskId, pub_user: window.pub_user, title: window.taskTitle, wxId: window.wxId}).then(function (res) {
          if (typeof res.data === 'string') {
            res.data = JSON.parse(res.data)
          }
          if (res.data.status === 1) {
            Vue.$tip({
              type: 5,
              msg: '接单成功',
              sureText: '朕知道了',
              success: function() {
                try {
                  window.close()
                  if ( window.WeixinJSBridge ) {
                    window.WeixinJSBridge.invoke('closeWindow');
                  }
                }
                catch (err) {
                  return false
                }
              }
            })
          } else if (res.data.status === 500) {
            window.location = AJAX.notLogin + 'help'
          } else {
            Vue.$tip({
              type: 1,
              msg: '接单失败，请重试',
              sureText: '朕知道了'
            })
          }
        })
      }
    },
    beenToken: function () {
      if (this.isYoursTask) {
        Vue.$tip({
          type: 0,
          msg: '你的任务已经有人接单了吗？',
          cancelText: '取消',
          sureText: '确认',
          success: hasBeenToken
        })
      }
      function hasBeenToken () {
        this.$http.post(AJAX.beenToken, {task_id: window.taskId, status: 1}).then(function (res) {
          if (typeof res.data === 'string') {
            res.data = JSON.parse(res.data)
          }
          if (res.data.status === 1) {
            Vue.$tip({
              type: 1,
              msg: '任务已被销毁',
              sureText: '朕知道了'
            })
          } else if (res.data.status === 500) {
            window.location = AJAX.notLogin + 'pub'
          } else {
            Vue.$tip({
              type: 1,
              msg: '任务销毁失败，请重试',
              sureText: '朕知道了'
            })
          }
        })
      }
    },
    cancelTask: function () {
      if (this.isYoursTask) {
        Vue.$tip({
          type: 0,
          msg: '确认取消这个任务吗？',
          cancelText: '再想想',
          sureText: '确认',
          success: cancelThisTask
        })
      }
      function cancelThisTask () {
        this.$http.post(AJAX.cancelTask, {task_id: window.taskId, status: 2}).then(function (res) {
          if (typeof res.data === 'string') {
            res.data = JSON.parse(res.data)
          }
          if (res.data.status === 1) {
            Vue.$tip({
              type: 1,
              msg: '任务已被取消',
              sureText: '朕知道了'
            })
          } else if (res.data.status === 500) {
            window.location = AJAX.notLogin + 'pub'
          } else {
            Vue.$tip({
              type: 1,
              msg: '任务取消失败，请重试',
              sureText: '朕知道了'
            })
          }
        })
      }
    },
    shutdown: function () {
      if (this.isAdmin) {
        Vue.$tip({
          type: 0,
          msg: '确认查封这个任务吗？',
          cancelText: '再想想',
          sureText: '确认',
          success: shutdown
        })
      }
      function shutdown () {
        this.$http.post(AJAX.shutdownTask, {task_id: window.taskId, status: 3, title: this.title}).then(function (res) {
          if (typeof res.data === 'string') {
            res.data = JSON.parse(res.data)
          }
          if (res.data.status === 1) {
            Vue.$tip({
              type: 1,
              msg: '任务已被查封',
              sureText: '朕知道了'
            })
          } else if (res.data.status === 500) {
            window.location = AJAX.notLogin + 'pub'
          } else {
            Vue.$tip({
              type: 1,
              msg: '任务查封失败，请重试',
              sureText: '朕知道了'
            })
          }
        })
      }
    }
  }
}
</script>

<style lang="scss">
@import "../../static/scss/_variables.scss";
.help-wrapper {
  box-sizing: border-box;
  width: 100%;
  height: 100%;
  padding: .7rem .48rem 0;
  text-align: left;
  h1 {
    color: #fff;
    font-size: .4rem;
  }
  .task-money {
    margin-top: .36rem;
    color: #ee1442;
    font-size: .4rem;
  }
  .task-detail {
    width: 100%;
    height: 3.96rem;
    box-sizing: border-box;
    border: 1px solid #0e3f50;
    background: #061d25;
    position: relative;
    margin-top: .5rem;
    padding: .74rem .34rem;
    color: #fff;
    p {
      overflow: scroll;
      width: 100%;
      height: 100%;
      font-size: .3rem;
    }
    .task-time {
      position: absolute;
      left: .34rem;
      bottom: .3rem;
      font-size: .26rem;
    }
    .top-bottom-line {
      position: absolute;
      left: 50%;
      top: 0;
      transform: translate(-50%, -2px);
      height: 100%;
      width: 2rem;
      border-top: 2px solid #2e656c;
      border-bottom: 2px solid #2e656c;
    }
  }
  .divide-line {
    width: 100%;
    height: 1px;
    position: relative;
    background-color: #0e3f50;
    margin-top: .58rem;
    .top-line {
      width: 2rem;
      height: 2px;
      position: absolute;
      top: -2px;
      left: 50%;
      transform: translateX(-50%);
      background-color: #2e656c;
    }
  }
  .operates {
    margin-top: .8rem;
    div {
      margin: 0 auto;
      color: #fff;
      background: #04333b;
      width: 2.85rem;
      height: .84rem;
      text-align: center;
      line-height: .84rem;
      font-size: .32rem;
      border-top: 1px solid #248888;
      border-bottom: 1px solid #248888;
      border-radius: .2rem;
      cursor: pointer;
    }
    .cancel-task {
      margin-top: .4rem;
    }
  }
}
.show-mask {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(2,9,11,.7);
  .been_token {
    position: absolute;
    top: 30%;
    left: 50%;
    transform: translate(-50%);
    width: 3rem;
    height: 3rem;
    background: url(../../static/image/been_token.png) 0 0 no-repeat;
    background-size: 100% 100%;
    text-align: center;
    color: #ee530c;
    font-size: .40rem;
    padding-top: 1rem;
    box-sizing: border-box;
    font-weight: 500;
  }
}
</style>

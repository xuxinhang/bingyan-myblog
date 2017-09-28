<template lang="jade">
  transition(name="fade", appear, v-on:after-enter="afterEnter")
    div.layer-container(v-if="msg")
      div.mask-bg(@click="msg=!msg")
      div.toast(v-show="toastMsg") {{toastMsg}}
      div.tip-container.type1(v-if="type===0 || 1")
        div.show-msg
          div
          div.text {{msg}}
        div.options
          span.cancel(@click="msg=!msg", v-if="type!==1") {{cancelText}}
          span.sure(@click="successCb") {{sureText}}
      div.tip-container.type2(v-if="type===2 || type === 4")
        div.show-msg
          div.title {{msg}}
          div.wxName
            input(type="text", placeholder="约吗？留个微信呗！", v-model="buyerWxName", v-if="type===2")
            input(type="text", placeholder="留下你的联系方式", v-model="pub_wxid", v-if="type===4")
        div.options
          span.cancel(@click="msg=!msg", v-if="type!==1") {{cancelText}}
          span.sure(@click="successCb") {{sureText}}
      div.tip-container.type3(v-if="type===3")
        div.show-msg
          div.title {{msg}}
          div.robot
        div.options
          span.sure(@click="successCb") {{sureText}}
      div.tip-container.type2(v-if="type===5")
        div.show-msg
          div.title {{msg}}
          p.tip(v-if="!copy_success && !copy_error") 如一键复制失败，请手动复制喔
          div.wxName#copy-area(v-if="!copy_success")
            input(type="text", v-model="pub_user", readonly, id="pub_user_copy", @click="selectAll")
        div.options
          span.sure(id="copy_btn", v-if="!copy_success && !copy_error", @click="oneCopy") {{sureText}}
          span.sure(@click="successCb", id="copy_text", v-if="copy_success || copy_error") {{sureText}}
</template>

<script>
export default {
  data () {
    return {
      buyerWxName: null,
      pub_wxid: null,
      copy_success: false,
      copy_error: false
    }
  },
  mounted () {
    var pubUserInput = document.getElementById('pub_user_copy')
    pubUserInput && pubUserInput.blur()
  },
  methods: {
    selectAll: function (e) {
      var pubUserInput = document.getElementById('pub_user_copy')
      pubUserInput.select()
    },
    oneCopy: function (e) {
      var pubUserInput = document.getElementById('pub_user_copy')
      pubUserInput.select()
      if (document.execCommand('copy')) {
        this.msg = '一键复制到剪贴板成功, 快去联系TA吧',
        this.sureText = '朕知道了'
        this.copy_success = true
      } else {
        this.msg = '复制失败，请手动长按复制',
        this.sureText = '朕知道了'
        this.copy_error = true
      }
    }
  }
}
</script>
<style lang='sass' scoped>
@import "../../../static/scss/_variables.scss";
.toast 
  position: fixed
  left: 50%
  top: 50%
  transform: translate(-50%, -50%)
  z-index: 200
  padding: .4rem .3rem
  font-size: .28rem
  background: rgba(17, 17, 17, 0.7)
  text-align: left
  border-radius: 5px
  color: #FFFFFF
.tip-container
  position: fixed
  left: 50%
  top: 50%
  transform: translate(-50%, -50%)
  z-index: 102
  width: 5.63rem
  height: 4.46rem
  text-align: center
  background-color: #ffffff
  border-radius: .15rem
  box-sizing: border-box
  display: flex
  flex-direction: column
  #copy-area
    input
      margin-top: .1rem
  .tip
    font-size: 12px;
  &.type1
    .show-msg
      height: 3.36rem
      box-sizing: border-box
      font-size: .4rem;
      color: #446996
      div:first-child
        height: 3.36rem
      div
        display: inline-block
        vertical-align: middle
      .text
        padding: .3rem
  &.type2
    padding: 0 .55rem
    .title
      margin-top: .62rem
      text-align: left
      font-size: .4rem
      color: #446996
    input
      display: block
      width: 100%
      border-radius: .1rem
      height: .75rem
      line-height: .75rem
      color: #33bda6
      font-size: .4rem
      border: 1px solid #bfbfbf
      box-sizing: border-box
      padding-left: .2rem
      margin-top: .4rem
      outline: none
      &:focus
        outline: none
    .btn
      border: none
      background: #446996
      color: #fff
      padding: 6px 12px 
      border-radiu: 4px
  &.type3
    .title
      margin-top: .4rem
      text-align: center
      font-size: .3rem
      padding: 0 .2rem
      color: #446996
    .robot
      width: 2rem
      height: 2rem
      margin: .2rem auto 0
      background: url('/static/image/robotqr.jpg') center center no-repeat
      background-size: 100% 100%
  .options
    position: absolute
    bottom: 0
    left: 0
    width: 100%
    display: flex
    height: 1rem
    line-height: 1rem
    border-top: 1px solid #446996
    span
      display: inline-block
      width: 50%
      box-sizing: border-box
      text-align: center
      flex: 1
      font-size: .32rem
    .cancel
      color: #33bda6
      border-right: 1px solid #446996
    .sure
      color: #446996
.mask-bg 
  position: fixed
  top: 0
  left: 0
  z-index: 101
  height: 100%
  width: 100%
  background: rgba(0,0,0,.5)
</style>

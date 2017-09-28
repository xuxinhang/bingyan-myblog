<template lang="jade">
  transition(name="fade", appear, v-on:after-enter="afterEnter")
    div.layer-container(v-if="msg")
      div.mask-bg
      div.toast(v-if="toastMsg") {{toastMsg}}
      div.tip-container(v-if="type<2")
        div.tip-bg
        p.big-p {{msg}}
        div.operates
          span.cancel(@click="msg=!msg", v-if="type===0") {{cancelText}}
          span.sure(@click="successCb()") {{sureText}}
      div.tip-container.type2(v-if="type===2")
        div.tip-bg
        div.yes-bg
        h4 {{msg}}
        p.little-p 任务已发布成功
        p.little-p 机器人正在为你寻找执行者
        div.operates
          span.sure(@click="successCb()") {{sureText}}
      div.tip-container.type3(v-if="type===3")
        div.tip-bg
        div.barcode-bg
        div.close-layer(@click="msg=!msg")
        p.barcode-img
          img(src="/static/image/robotqr.jpg")
        p.little-p 接单需要添加机器人为好友
        p.little-p 请扫描图中二维码，添加机器人为好友
      div.tip-container.type4(v-if="type === 4")
        p.big-p {{msg}}
        input.wx-id(type="text", placeholder="输入你的微信号", @change="handleWxInput", v-model="wxId")
        div.operates
          span.sure(@click="successCb") {{sureText}}
        div.tip-bg
      div.tip-container.type2.type5(v-if="type===5")
        div.tip-bg
        div.yes-bg
        .big-p 对方已收到您的联系方式，请等待回应。
        div.operates
          span.sure(@click="successCb()") {{sureText}}
      div.tip-container.type4(v-if="type === 6")
        p.big-p {{msg}}
        input.wx-id(type="text", placeholder="输入你的微信号", @change="handleWxInput", v-model="pub_user")
        div.operates
          span.sure(@click="successCb") {{sureText}}
        div.tip-bg
      div.tip-container.type4(v-if="type === 7")
        p.big-p {{msg}}
        p.tip(v-if="!copy_success && !copy_error") 如一键复制失败，请手动复制喔
        input.wx-id(type="text", v-model="pub_user", id="pub_user_copy", readonly, v-if="!copy_success", @click="selectAll")
        div.operates
          span.sure(id="copy_btn", v-if="!copy_success && !copy_error", @click="oneCopy") {{sureText}}
          span.sure(@click="successCb", id="copy_text", v-if="copy_success || copy_error") {{sureText}}
        div.tip-bg
</template>

<script>
export default {
  data () {
    return {
      pub_user: '',
      copy_success: false,
      copy_error: false
    }
  },
  mounted () {
    var textInput = document.getElementById('pub_user_copy')
    textInput && textInput.blur()
  },
  methods: {
    handleWxInput(e) {
      window.wxId = e.target.value
    },
    selectAll: function (e) {
      var textInput = document.getElementById('pub_user_copy')
      textInput.select()
    },
    oneCopy: function (e) {
      var textInput = document.getElementById('pub_user_copy')
      textInput.select()
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
<style lang='scss' scoped>
@import "../../../static/scss/_variables.scss";
.toast {
  position: fixed;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  z-index: 102;
  padding: .4rem .3rem;
  font-size: .28rem;
  background: rgba(17, 17, 17, 0.7);
  text-align: left;
  border-radius: 5px;
  color: #FFFFFF;
}
.tip-container{
  position: fixed;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  z-index: 99;
  width: 4.64rem;
  text-align: center;
  font-size: .14rem;
  color: #fff;
  padding: .6rem .45rem .2rem;
  box-sizing: border-box;
  &.type4 {
    .big-p {
      font-size: .32rem
    }
    .tip {
      text-align: left;
      color: #4ed7e1;
      font-size: 12px;
      position: relative;
      z-index: 101;
      margin: .1rem 0;
    }
  }
  &.type5 {
    .big-p {
      position: relative;
      z-index: 101;
      margin-top: .2rem;
      color: $baseTitleColor;
      font-size: .32rem;
      text-align: left;
    }
  }
  .wx-id {
    position: relative;
    z-index: 101;
    top: 0;
    left: 0;
    margin-top: .2rem;
    height: .5rem;
    line-height: .5rem;
    font-size: .3rem;
    width: 100%;
    background-color: #0d3848;
    border: 1px solid #264c5f;
    border-radius: 2px;
    color: #4ed7e1;
    padding: .1rem 0;
    outline: none;
    text-indent: .1rem;
  }
  &.type2 {
    .tip-bg {
      background: rgba(4,32,37,.6) url(../../../static/image/layer_border_center.png) 0 0 no-repeat;
      background-size: 100% 100%;
    }
    .yes-bg {
      position: absolute;
      top: 0;
      left: 50%;
      z-index: 101;
      transform: translate(-50%, -48%);
      height: 1.5rem;
      width: 1.5rem;
      background: url(../../../static/image/yes_bg.png) 0 0 no-repeat;
      background-size: 100% 100%;
    }
    .operates {
      margin-top: .4rem;
    }
  }
  &.type3 {
    .tip-bg {
      background: rgba(4,32,37,.6) url(../../../static/image/layer_border_center.png) 0 0 no-repeat;
      background-size: 100% 100%;
    }
    .barcode-bg {
      position: absolute;
      top: 0;
      left: 50%;
      z-index: 101;
      transform: translate(-50%, -48%);
      height: 1.5rem;
      width: 1.5rem;
      background: url(../../../static/image/barcode_bg.png) 0 0 no-repeat;
      background-size: 100% 100%;
    }
    .barcode-img {
      position: relative;
      z-index: 100;
      margin: .6rem 0 .1rem;
      img {
        display: inline-block;
        width: 1.55rem;
        height: 1.55rem;
      }
    }
    .little-p {
      letter-spacing: 0;
    }
    .little-p:last-child {
      margin-bottom: .24rem;
    }
    .close-layer {
      position: absolute;
      top: .25rem;
      right: .25rem;
      z-index: 100;
      width: .22rem;
      height: .22rem;
      background: url(../../../static/image/close.png) 0 0 no-repeat;
      background-size: 100% 100%;
    }
  }
  .tip-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 99;
    box-sizing: border-box;
    background: rgba(4,32,37,.6) url(../../../static/image/layer_border.png) 0 0 no-repeat;
    background-size: 100% 100%;
  }
  h4 {
    color: $baseTitleColor;
    text-align: center;
    position: relative;
    z-index: 100;
    font-size: .56rem;
    margin-top: .4rem;
  }
  p.big-p {
    position: relative;
    z-index: 100;
    font-size: .38rem;
    text-align: left;
    color: $baseTitleColor;
  }
  p.little-p {
    position: relative;
    z-index: 100;
    text-align: center;
    font-size: .2rem;
    color: rgba(78,215,215,0.6);
  }
  .operates {
    text-align: center;
    position: relative;
    z-index: 101;
    margin: 1rem 0 .25rem;
    span {
      padding: .1rem .4rem;
      font-size: .28rem;
      background: #042025 url(../../../static/image/button_border.png) 0 0 no-repeat;
      background-size: 100% 100%;
    }
    .sure {
      color: #fe545c;
    }
    .cancel {
      color: #379da6;
      margin-right: .6rem;
    }
  }
}
.mask-bg {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 98;
  height: 100%;
  width: 100%;
  background: rgba(1,7,9,.7);
}
.fade-enter-active, .fade-leave-active {
  transition: opacity .3s ease, top .3s ease;
}
.fade-enter, .fade-leave-active {
  top: -1rem;
  opacity: 0;
}
</style>

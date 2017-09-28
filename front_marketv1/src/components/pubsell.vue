<template lang="jade">
.sell-wrapper
  form.pub-sell(v-if="!success")
    div.form-control.name
      label(for="sell-name") 物品名称：
      input(type="text", id="sell-name", placeholder="在这里填入物品名称喔~", maxLength="20", v-model="name")
    div.form-control.desc
      label(for="sell-desc") 添加描述：
      textarea(id="sell-desc", placeholder="在这里写下物品的详细描述喔~", maxLength="250", v-model="desc")
    div.form-control.how-mouch
      label(for="sell-money") 多少钱呢：
      input(type="number", id="sell-money", max="100000", min="0", v-model="money")
      div(@click="canbeLower = !canbeLower")
        i(:class="{'active': canbeLower}")
          span
        span 可小刀
    div.add-photo
      img(:src="img", alt="商品图片", v-if="img", :class="{vertical: isImgVertical}")
      form
        p.icon(v-show="!img")
          img(src="/static/image/camera.png", alt="添加图片")
        p.text(v-show="!img") 添加图片
        input(type="file", id="img_file", @change="fileChange()", accept="image/*")
  div.submit-sell(@click="submit", v-if="!success") 发布
  div.tree-btn(v-if="success")
    div.btn(@click="Iknow") 朕知道了
  div.sell-yes(v-if="success")
    p 发布成功！
  div.mask-img-error(v-if="imgError")
    div.img-error-wrapper
      div.error !
      h4 上传图片失败！
      p 目前仅支持jpg,png,gif格式下的图片
      div.button(@click="imgError=!imgError") 朕知道了
</template>

<script>
let AJAX = require('../../static/js/ajax.js')
// 引入插件
let PubSell = require('../plugins/SellTip/SellTip.js')
let Vue = require('vue')
Vue.use(PubSell)

// dataurl转blob对象
const dataURLtoBlob = function (dataurl) {
    var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
    while(n--){
        u8arr[n] = bstr.charCodeAt(n);
    }
    return new Blob([u8arr], {type:mime});
}

export default {
  data () {
    return {
      name: null,
      desc: null,
      money: null,
      img: null,
      imgName: null,
      canbeLower: false,
      success: false,
      pid: null,
      isRobotFriend: false,
      imgError: false,
      isImgVertical: false,
    }
  },
  mounted() {
    // this.$http(AJAX.checkFriend).then(function (res) {
    //   if (typeof res.data === 'string') {
    //     res.data = JSON.parse(res.data)
    //   }
    //   if (res.data.status === 1) {
    //     this.isRobotFriend = res.data.data.isFriend
    //   }
    // })
  },
  methods: {
    Iknow: function () {
      window.location.href = 'market'
      // window.location.href = 'buy?pid=' + this.pid
    },
    submit: function () {
      // 添加机器人限制
      // if (!this.isRobotFriend) {
      //   Vue.$pubtip({
      //     type: 3,
      //     msg: '需先扫码添加机器人为好友喔',
      //     sureText: '朕知道了'
      //   })
      //   return
      // }
      var name = this.name
      var desc = this.desc
      var money = this.money
      var imgName = this.imgName
      var canbeLower = Number(this.canbeLower)
      var pub_wxid = window.pub_wxid 
      if (!name) {
        Vue.$pubtip({type: 0, msg: '还没有填写商品名称喔~', sureText: '继续填写'})
        return
      }
      if (!money) {
        Vue.$pubtip({type: 0, msg: '还没有填写商品价格喔~', sureText: '继续填写'})
        return 
      }
      // 发布者留下微信号
      if (!pub_wxid) {
        Vue.$pubtip({
          type: 4,
          msg: '留下你的微信号，我们将转发给购买者',
          sureText: '确定',
          cancelText: '取消',
          pub_wxid: window.localStorage.getItem('pub_wxid'),
          success: getWxid.bind(this)
        })
        return 
      }
      var that = this
      function getWxid (e) {
        if (!window.pub_wxid) {
          Vue.$pubtip({
            type: 1,
            msg: '没有微信号，买家找不到你喔', 
            sureText: '继续填写',
            success: function () {
              Vue.$pubtip({
                type: 4,
                msg: '留下微信号，等待买家与您联系',
                sureText: '确定',
                cancelText: '取消',
                success: getWxid
              })
            }
          }) 
          return
        } else {
          window.localStorage.setItem('pub_wxid', window.pub_wxid)
          this.submit()
        }
      }
      
      window.isPubing = true
      var formData = new FormData()
      if (this.img) {
        var img = dataURLtoBlob(this.img)
        formData.append('img', img)
        formData.append('imgName', imgName)
      }
      formData.append('title', name)
      formData.append('detail', desc)
      formData.append('money', money)
      formData.append('isLittle', canbeLower)
      formData.append('pub_wxid', pub_wxid)
      Vue.$pubtip({
        type: 1,
        msg: '确认发布？',
        sureText: '确认',
        cancelText: '再想想',
        success: postTask
      })
      function postTask () {
        // 关闭弹窗
        Vue.$pubtip({
          type: 1,
          msg: null,
          sureText: '朕知道了'
        })
        that.$http.post(AJAX.pubsell, formData).then(function (res) {
          if (typeof res.data === 'string') {
            res.data = JSON.parse(res.data)
          }
          window.isPubing = false
          switch (res.data.status) {
            case 1:
              that.success = true
              that.pid = res.data.data.pid
              break
            case 500:
              window.location = AJAX.notLogin + 'pubsell'
              break
            case 101:
              Vue.$pubtip({
                type: 1,
                msg: '缺少必要信息喔',
                sureText: '朕知道了'
              })
              break
            case 203:
              Vue.$pubtip({
                type: 3,
                msg: '不是好友'
              })
              break
            case 300:
              Vue.$pubtip({
                type: 1,
                msg: '距离上次发布还不到五分钟喔',
                sureText: '朕知道了'
              })
              break
            case 301:
              Vue.$pubtip({
                type: 1,
                msg: '每天有五次的任务上限喔，请明天再发吧！',
                sureText: '朕知道了'
              })
              break
            case 401:
              Vue.$pubtip({
                type: 1,
                msg: '图片格式不正确，新建失败',
                sureText: '朕知道了'
              })
            default:
              Vue.$pubtip({
                type: 1,
                msg: '发布失败，请重新发布',
                sureText: '朕知道了'
              })
              break
          }
        })
      }
    },
    fileChange: function() {
      var fileInput = document.getElementById('img_file');
      // 检查文件是否选择:
      if (!fileInput.value) {
          return
      }
      // 获取File引用:
      var file = fileInput.files[0];
      console.log(file.type)
      // 获取File信息:
      if (file.type !== 'image/jpeg' && file.type !== 'image/png' && file.type !== 'image/gif' && file.type !== 'image/jpg') {
          this.imgError = true
          return
      }
      // this.imgName = file.name.replace(/\.(png|jpg|gif)$/, '');
      this.imgName = file.name
      var that = this;
      // 读取文件:
      var reader = new FileReader();
      reader.onload = function(e) {
        var data = e.target.result // 'data:image/jpeg;base64,/9j/4AAQSk...(base64编码)...'
        var img = new Image()
        img.src = data;
        img.onload = function () {
          var canvas = document.createElement('canvas');
          canvas.width = img.naturalWidth
          canvas.height = img.naturalHeight
          that.isImgVertical = canvas.height > canvas.width ? true : false
          canvas.getContext("2d").drawImage(img, 0, 0)
          var compressBase64 = canvas.toDataURL(file.type, 5 / 100)
          that.img = compressBase64
        }
      };
      // 以DataURL的形式读取文件:
      reader.readAsDataURL(file);
    }
  }
}
</script>

<style lang="sass">
.pub-sell
  color: #446996
  padding: .85rem .42rem 0
.form-control
  margin-top: .2rem
  label
    font-size: .3rem
    color: #446996
    font-weight: bold
  input
    height: .75rem
    line-height: .75rem
    font-size: .32rem
    color: #25a690
    padding: 0 .25rem
    border-radius: .1rem
    border: none
    background-color: #ffffff
    outline: none
    box-sizing: border-box
.name
  input
    display: block
    width: 100%
    margin-top: .1rem
.desc
  textarea
    margin-top: .1rem
    display: block
    height: 2.2rem
    line-height: 1.3
    font-size: .32rem
    color: #25a690
    padding: .25rem
    border-radius: .1rem
    border: none
    background-color: #ffffff
    outline: none
    box-sizing: border-box
    overflow-y: auto
    width: 100%
    resize: none
.how-mouch
  input
    width: 1.77rem
  div
    float: right
    margin-top: .15rem
    i
      display: inline-block
      vertical-align: middle
      width: .32rem
      height: .32rem
      border-radius: 50%
      border: 2px solid #446996
      text-align: center
      position: relative
      box-sizing: border-box
      position: relative
      top: 0
      left: 0
      span
        position: absolute
        left: 50%
        top: 50%
        transform: translate(-50,-50%)
      &.active
        span
          background-color: #33bda6
      span
        display: inline-block
        position: absolute
        left: 50%
        top: 50%
        transform: translate(-50%, -50%)
        width: .15rem
        height: .15rem
        margin: 0
        border-radius: 50%
        background-color: transparent
        transition: background-color .4s ease
    span
      vertical-align: middle
      font-size: .3rem
      font-weight: bold
      margin-left: .15rem
.add-photo
  width: 5.23rem
  height: 4.21rem
  margin: .3rem auto 0
  border-radius: .1rem
  background-color: #fff
  text-align: center
  position: relative
  z-index: 1
  overflow: hidden
  & > img
    position: absolute
    z-index: 3
    top: 50%
    left: 50%
    transform: translate(-50%, -50%)
    width: 100%
    height: auto
  & > img.vertical
    width: auto
    height: 100%
  form
    position: relative
    z-index: 3
    width: 100%
    height: 100%
    input
      position: absolute
      top: 0
      left: 0
      width: 100%
      height: 100%
      opacity: 0
  .icon
    padding-top: 1.11rem
    img
      width: 1.68rem
      height: 1.32rem
  .text
    margin-top: .2rem
    font-size: .3rem
    color: #446996
    font-weight: bold
.submit-sell
  position: fixed
  bottom: 0
  left: 0
  height: 1rem
  text-align: center
  line-height: 1rem
  color: #ffffff
  font-size: .4rem
  width: 100%
  background-color: #f1787d
.tree-btn
  position: fixed
  bottom: 0
  left: 0
  height: 4.33rem
  text-align: center
  width: 100%
  background: url(/static/image/tree.png) 0 0 no-repeat
  background-size: 100% 100%;
  .btn
    margin: 2.4rem auto 0
    width: 2.88rem
    height: 1rem
    line-height: 1rem
    font-size: .4rem
    color: #fff
    text-align: center
    border-radius: .5rem
    background-color: #f1787d
.sell-yes
  position: fixed
  top: 1.7rem
  left: 0
  height: 4.33rem
  text-align: center
  width: 100%
  background: url(/static/image/sell_yes.png) center 0 no-repeat
  background-size: 3.29rem 3.65rem
  p
    margin-top: 5.1rem
    font-size: .5rem
    color: #446996
    text-align: center
.mask-img-error
  position: fixed
  top: 0
  left: 0
  z-index: 200
  width: 100%
  height: 100%
  background-color: rgba(0,0,0,.5)
.img-error-wrapper
  position: absolute
  top: 2.5rem
  left: 50%
  transform: translateX(-50%)
  width: 5.63rem
  height: 5.2rem
  background: #fff url(../../static/image/tree_bg.png) 0 bottom no-repeat
  background-size: 100% auto
  border-radius: .1rem
  .error
    width: 1.8rem
    height: 1.8rem
    margin: .4rem auto 0
    border-radius: 50%
    background-color: #f1787d
    text-align: center
    line-height: 1.8rem
    color: #fff
    font-size: 1.2rem
  h4
    margin-top: .35rem
    color: #446996
    font-size: .4rem
    text-align: center
  p
    margin-top: .25rem
    color: #446996
    font-size: .24rem
    text-align: center
  .button
    height: .67rem
    width: 1.93rem
    background-color: #f1787d
    color: #fff
    border-radius: .335rem
    line-height: .67rem
    text-align: center
    margin: .55rem auto 0
</style>

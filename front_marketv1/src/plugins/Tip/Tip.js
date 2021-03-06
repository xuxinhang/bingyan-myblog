/*
* 弹出层插件，按有无上边框图片可分为两种，又按单按钮还是双按钮分为两种
* 有无图片分两个vue文件组件写。单双按钮通过v-if控制显示。
* 配置参数
* @param
*  {
*    type: Number (0: 无图片，双按钮。1: 无图片, 单按钮。2： 有图，发布成功.3: 有图，机器人二维码),
*    msg: String (表示显示的信息),
*    sureText: String (表示确定按钮显示的文字，默认确定)
*    cancelText: String (表示取消按钮显示的文字，默认取消)
*    success: Function (表示点击确定按钮的回调函数)
*  }
*
*/


module.exports.install = function (Vue) {
  let TipConstructor = Vue.extend(require('./Tip.vue'))
  let TipInstance = null
  let body = document.getElementsByTagName('body')[0]

  var Fun = function (options, callback) {
    options = options || { type: 1, msg: '', success: function () {}, cancelText: '取消', sureText: '确认'}
    if (TipInstance) {
      TipInstance.msg = options.msg
      TipInstance.type = options.type
      TipInstance.success = options.success
      TipInstance.sureText = options.sureText
      TipInstance.cancelText = options.cancelText
      TipInstance.pub_user = options.pub_user || ''
      TipInstance.copy_success = options.copy_success || false
      TipInstance.copy_error = options.copy_error || false
      TipInstance.wxId = null
      TipInstance.toastMsg = ''
      return
    }
    TipInstance = new TipConstructor({
      el: document.createElement('div'),
      data () {
        return {
          msg: options.msg,
          type: options.type,
          success: options.success,
          cancelText: options.cancelText,
          sureText: options.sureText,
          pub_user: options.pub_user || '',
          copy_success: options.copy_success || false,
          copy_error: options.copy_error || false,
          toastMsg: '',
          wxId: null
        }
      },
      methods: {
        afterEnter: function () {
          // if (this.msg) setTimeout(() => { this.msg = null }, 2500)
        },
        successCb: function (e) {
          if (this.type === 6) {
            var reg = /[\u4e00-\u9fa5]/g
            if (!this.pub_user || reg.test(this.pub_user)) {
              this.toastMsg = '请输入正确的微信号，方便接单者查找'
              setTimeout(() => this.toastMsg = '', 2000)
              return
            }
            else window.pub_wxid = this.pub_user
          }
          if (this.wxId === null && this.type === 4) {
            this.wxId = ''
            return
          }
          if (this.success) this.success(e)
          this.msg = null
        }
      },
      // 挂载，类似vue1.0中的ready
      mounted () {
        this.$nextTick(function () {
          body.appendChild(this.$el)
        })
        // setTimeout(() => { this.msg = null }, 2500)
      }
    })
    if (callback) {
      setTimeout(function () {
        callback && callback()
      }, 1000)
    }
    return true;
  }

  Vue.prototype.$tip = Fun
  Vue.$tip = Fun
}

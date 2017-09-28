/* eslint-disable no-new */
require('../../../static/scss/buy.scss')
import Vue from 'vue'
import VueResource from 'vue-resource'
import VueRouter from 'vue-router'

// 加载自定义插件
// let tipPlugin = require('../../plugins/Tip/Tip.js')

// 引入自定义js文件
let baseJs = require('../../../static/js/base.js')
let getQueryString = require('../../../static/js/tool.js').getQueryString

// 设置根节点字体
baseJs.setFontSize()
baseJs.setBorderHeight()
// 引入组件
import BuyContainer from '../../components/buy.vue'

let AJAX = require('../../../static/js/ajax.js')


Vue.use(VueResource)
Vue.use(VueRouter)


// 解析json数据
Vue.http.options.emulateJSON = true

// 生成vue实例
new Vue({
  data: {
    offsetTop: 0,
    detail: null,
    pid: null,
  },
  components: {
    'BuyContainer': BuyContainer
  },
  mounted() {
    this.getData()
  },
  methods: {
    getData() {
      const goodsId = getQueryString('pid')

      this.pid = goodsId
      this.$http.get(AJAX.malldetail + "?pid=" + goodsId).then(function (res) {
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
}).$mount('#app')

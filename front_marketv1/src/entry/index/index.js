/* eslint-disable no-new */
require('../../../static/scss/index.scss')
import Vue from 'vue'
import VueResource from 'vue-resource'
import VueRouter from 'vue-router'

// 加载自定义插件
// let tipPlugin = require('../../plugins/Tip/Tip.js')

// 引入自定义js文件
let baseJs = require('../../../static/js/base.js')
baseJs.setFontSize()
baseJs.setBorderHeight()

// 引入组件
import PubTask from '../../components/pub.vue'

Vue.use(VueResource)
Vue.use(VueRouter)

// 解析json数据
Vue.http.options.emulateJSON = true

// 生成vue实例
new Vue({
  data: {
    offsetTop: 0
  },
  components: {
    "pubTask": PubTask
  }
}).$mount('#app')

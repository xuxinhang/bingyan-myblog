/* eslint-disable no-new */
require('../../../static/scss/pub.scss')
import Vue from 'vue'
import VueResource from 'vue-resource'
import VueRouter from 'vue-router'

// 加载自定义插件

// 引入自定义js文件
let baseJs = require('../../../static/js/base.js')
// 设置根节点字体
baseJs.setFontSize()
baseJs.setBorderHeight()
// 引入组件
import PubContainer from '../../components/pubsell.vue'

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
    'PubContainer': PubContainer
  }
}).$mount('#app')

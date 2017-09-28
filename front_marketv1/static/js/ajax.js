/*这里是生产环境*/
const BASE_API = 'http://bbm.hustonline.net'

/*这里是夏浩的开发环境*/
// const BASE_API = 'http://bbmtest.hustonline.net'

/*这里是夏浩的开发环境*/
// const BASE_API = ''

module.exports = {
    // 发布任务
    publishTask: BASE_API + '/task/add',
    // 检查是否为机器人好友
    checkFriend: BASE_API + '/task/checkFriend',
    // 获取任务详情
    checkTask: BASE_API + '/task/detail',
    // 接单
    helpNow: BASE_API + '/task/help',
    // 任务已被接
    beenToken: BASE_API + '/task/confirm',
    // 取消任务
    cancelTask: BASE_API + '/task/confirm',
    // 查封任务
    shutdownTask: BASE_API + '/task/confirm',
    // 未登录跳转地址
    notLogin: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx690cda5c2ea251b2&redirect_uri=http%3A%2F%2Fweixin.bigtech.cc%2Fbbm%2Flogin&response_type=code&scope=snsapi_userinfo&state=',
    // 任务详情页
    taskDetailPage: BASE_API + '/public/help?task_id=',

    // 小二集市列表
    market: BASE_API + '/mall/list',
    // 发布商品任务
    pubsell: BASE_API + '/mall/add',
    // 查看商品任务
    malldetail: BASE_API + '/mall/detail',
    // 接任务
    buy: BASE_API + '/mall/buy',
    // 商品卖出
    sellout: BASE_API + '/mall/confirm',
    // 商品下架
    sellback: BASE_API + '/mall/confirm',
    // 查封
    shutdown: BASE_API + '/mall/confirm'
}

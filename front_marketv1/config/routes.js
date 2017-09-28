module.exports = function (app) {
  app.post('/task/add', function (req, res) {
    console.log(req.params)
    res.json({
      "status": 1,
      "data": {
        "task_id": 1212
      }
    })
  })
  app.post('/mall/add', function (req, res) {
    console.log(req.params)
    res.json({
      "status": 1,
      "data": {
        "task_id": 1212
      }
    })
  })
  app.get('/task/detail', function (req, res) {
    res.json({
      "status": 1,
      "data": {
        "title": "这里是标题",
        "detail": "在东二楼取一份文件，找曹老师，办理一份重要加急加急文件在东二楼取一份文件，找曹老师，办理一份重要加急加急文件在东二楼取一份文件，找曹老师，办理一份重要加急加急文件在东二楼取一份文件，找曹老师，办理一份重要加急加急文件在东二楼取一份文件，找曹老师，办理一份重要加急加急文件在东二楼取一份文件，找曹老师，办理一份重要加急加急文件在东二楼取一份文件，找曹老师，办理一份重要加急加急文件在东二楼取一份文件，找曹老师，办理一份重要加急加急文件在东二楼取一份文件，找曹老师，办理一份重要加急加急文件在东二楼取一份文件，找曹老师，办理一份重要加急加急文件在东二楼取一份文件，找曹老师，办理一份重要加急加急文件在东二楼取一份文件，找曹老师，办理一份重要加急加急文件在东二楼取一份文件，找曹老师，办理一份重要加急加急文件在东二楼取一份文件，找曹老师，办理一份重要加急加急文件在东二楼取一份文件，找曹老师，办理一份重要加急加急文件在东二楼取一份文件，找曹老师，办理一份重要加急加急文件在东二楼取一份文件，找曹老师，办理一份重要加急加急文件在东二楼取一份文件，找曹老师，办理一份重要加急加急文件在东二楼取一份文件，找曹老师，办理一份重要加急加急文件在东二楼取一份文件，找曹老师，办理一份重要加急加急文件在东二楼取一份文件，找曹老师，办理一份重要加急加急文件在东二楼取一份文件，找曹老师，办理一份重要加急加急文件",
        "money": "1000",
        "end_date": "明晚之前",
        "pub_user": "热干面还是不加海鲜汁",
        "user_type": 2,
        "task_status": 0,
        "isFriend": 1
      }
    })
  })
  app.get('/task/checkFriend', function (req, res) {
    res.json({
      "status": 1,
      "data": {
        "isFriend": true
      }
    })
  })
  app.post('/task/help', function (req, res) {
    res.json({
      "status": 1,
      "msg": "success"
    })
  })
  app.post('/task/accepted', function (req, res) {
    res.json({
      "status": 1,
      "msg": 'success'
    })
  })
  app.post('/task/cancel', function (req, res) {
    res.json({
      "status": 1,
      "msg": 'success'
    })
  })
  app.get('/mall/list', function (req, res) {
    res.json({
      "status": 1,
      "data": [{
        "status": 1,
        "title": "魔法医生茉莉系列洗面奶",
        "detail": "集贸屈臣氏三百多买的洗面奶和巴拉巴拉吧lablab啦啦啦粑粑",
        "money": "180",
        "isLittle": true,
        "pid": 1212
      }, {
        "status": 0,
        "title": "魔法医生茉莉系列洗面奶",
        "detail": "集贸屈臣氏三百多买的洗面奶和巴拉巴拉吧lablab啦啦啦粑粑",
        "money": "180",
        "isLittle": true,
        "pid": 1212
      }, {
        "status": 2,
        "title": "魔法医生茉莉系列洗面奶",
        "detail": "集贸屈臣氏三百多买的洗面奶和巴拉巴拉吧lablab啦啦啦粑粑",
        "money": "180",
        "isLittle": false
      }, {
        "status": 1,
        "title": "魔法医生茉莉系列洗面奶",
        "detail": "集贸屈臣氏三百多买的洗面奶和巴拉巴拉吧lablab啦啦啦粑粑",
        "money": "180",
        "isLittle": false
      }, {
        "status": 0,
        "title": "魔法医生茉莉系列洗面奶",
        "detail": "集贸屈臣氏三百多买的洗面奶和巴拉巴拉吧lablab啦啦啦粑粑",
        "money": "180",
        "isLittle": true
      }, {
        "status": 1,
        "title": "魔法医生茉莉系列洗面奶",
        "detail": "集贸屈臣氏三百多买的洗面奶和巴拉巴拉吧lablab啦啦啦粑粑",
        "money": "180",
        "isLittle": true
      }]
    })
  }),
  app.get('/mall/detail', function (req, res) {
    res.json({
      "status": 1,
      "msg": "success",
      "data": {
        "pid": "测试的ID",
        "title": "测试标题标题标题标题标题",
        "detail": "快来买啊啊啊啊啊啊快来买啊啊啊啊啊啊啊的减肥的咖啡发哈的发挥快来买啊啊啊啊啊啊啊的减肥的咖啡发哈的发挥快来买啊啊啊啊啊啊啊的减肥的咖啡发哈的发挥快来买啊啊啊啊啊啊啊的减肥的咖啡发哈的发挥快来买啊啊啊啊啊啊啊的减肥的咖啡发哈的发挥啊的减肥的咖啡发哈的发挥快来买啊啊啊啊啊啊啊的减肥的咖啡发哈的发挥快来买啊啊啊啊啊啊啊的减肥的咖啡发哈的发挥快来买啊啊啊啊啊啊啊的减肥的咖啡发哈的发挥快来买啊啊啊啊啊啊啊的减肥的咖啡发哈的发挥快来买啊啊啊啊啊啊啊的减肥的咖啡发哈的发挥",
        "money": "200",
        "pub_wxid": "热干面干嘛要加海鲜汁",
        "user_type": 2,
        "pic": "/static/image/sell.png",
        "isLittle": true,
        "isAdmin": 0,
        "status": 0
      }
    })
  })
  app.post('/mall/buy', function (req, res) {
    res.json({
      "status": 1,
      "msg": "success"
    })
  })
  app.post('/mall/confirm', function (req, res) {
    res.json({
      "status": 1,
      "msg": "success"
    })
  })
}

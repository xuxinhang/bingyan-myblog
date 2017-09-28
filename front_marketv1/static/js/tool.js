module.exports = {
  formatDate(date) {
    date = date || Date()
    date = new Date(date)
    var year = date.getFullYear()
    var month = addZero(date.getMonth() + 1)
    var day = addZero(date.getDate())
    return {
      year: year,
      month: month,
      day: day
    }
    function addZero(num) {
      if(num < 10) return '0' + num
    }
  },
  getQueryString(name) {
    const reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)")
    const r = window.location.search.substr(1).match(reg)
    if(r != null) return unescape(r[2])
    return null;
  },
}

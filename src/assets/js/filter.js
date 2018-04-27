import Vue from 'vue'
import moment from 'moment'
export default (function () {
  Vue.filter('status', function (value) {
    if (value == 1) {
      return '启用'
    } else if (value == 0) {
      return '禁用'
    } else {
      return '未知状态'
    }
  })
  Vue.filter('rules', function (value) {
    return value
  })
  Vue.filter('fileLink', function (value) {
    const link = window.imgUrl + value
    return link
  })
  Vue.filter('numToString', function (value) {
    const string = value.toString()
    return string
  })
  Vue.filter('time', function (value) {
    let day = moment.unix(value)
    let date = moment(day).format('YYYY/MM/DD H:mm')
    return date
  })
  Vue.filter('date', function (value) {
    let day = moment.unix(value)
    let date = moment(day).format('YYYY/MM/DD')
    return date
  })
  Vue.filter('abstract', function (value) {
    let abstract = ''
    if (value.length > 70) {
      abstract = value.substr(0, 70) + '...'
    } else {
      abstract = value
    }
    return abstract
  })
  Vue.filter('posStatus', function (value) {
    let status = ''
    switch (value) {
      case 1:
        status = '在职'
        break
      case 2:
        status = '待入职'
        break
      case 3:
        status = '离职'
        break
    }
    return status
  })
  Vue.filter('template', function (value) {
    let template = ''
    if (value == '') {
      template = '上传'
    } else {
      template = '上传更新'
    }
    return template
  })
})()

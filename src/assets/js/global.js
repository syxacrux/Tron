import store from '@/vuex/store'
import router from '@/router/index.js'
import Lockr from 'lockr'
import bus from '@/assets/js/bus.js'

const commonFn = {
  j2s(obj) {
    return JSON.stringify(obj)
  },
  //时间转换
  j2time(obj) {
    let year = obj.getFullYear()
    let month = obj.getMonth() + 1 < 10 ? '0' + (obj.getMonth() + 1) : obj.getMonth() + 1
    let date = obj.getDate()  < 10 ? '0' + obj.getDate() : obj.getDate()
    let hour = obj.getHours() < 10 ? '0' + obj.getHours() : obj.getHours()
    let min = obj.getMinutes() < 10 ? '0' + obj.getMinutes() : obj.getMinutes()
    let seconds = obj.getSeconds() < 10 ? '0' + obj.getSeconds() : obj.getSeconds()
    return year + '-' + month + '-' + date + ' ' + hour + ':' + min + ':' + seconds
  },
  shallowRefresh(name) {
    router.replace({ path: '/refresh', query: { name: name }})
  },
  closeGlobalLoading() {
    setTimeout(() => {
      store.dispatch('showLoading', false)
    }, 0)
  },
  openGlobalLoading() {
    setTimeout(() => {
      store.dispatch('showLoading', true)
    }, 0)
  },
  cloneJson(obj) {
    return JSON.parse(JSON.stringify(obj))
  },
  toastMsg(type, msg) {
    switch (type) {
      case 'normal':
        bus.$message(msg)
        break
      case 'success':
        bus.$message({
          message: msg,
          type: 'success'
        })
        break
      case 'warning':
        bus.$message({
          message: msg,
          type: 'warning'
        })
        break
      case 'error':
        bus.$message.error(msg)
        break
    }
  },
  clearVuex(cate) {
    store.dispatch(cate, [])
  },
  getHasRule(val) {
    const moduleRule = 'admin'
    let userInfo = store.state.users
    if (userInfo.id == 1) {
      return true
    } else {
      let authList = store.state.rules
      const ruleName = moduleRule + '-' + val
      return _.includes(authList, ruleName)
    }
  }
}

export default commonFn

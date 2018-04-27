// 依赖
import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '@/vuex/store.js'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'
import baseHttp from '@/assets/js/base_http.js'
import Lockr from 'lockr'
import config from '@/assets/js/config.js'
import _g from '@/assets/js/global.js'
// 组件
import Login from '@/components/Account/Login.vue'
import refresh from '@/components/refresh.vue'
import Home from '@/components/Home.vue'
// 系统配置-菜单管理
import menuList from '@/components/Admin/system/menu/list.vue'
import menuAdd from '@/components/Admin/system/menu/add.vue'
import menuEdit from '@/components/Admin/system/menu/edit.vue'
// 系统配置-权限参数
import systemConfig from '@/components/Admin/system/config/add.vue'
// 系统配置-权限规则
import ruleList from '@/components/Admin/system/rule/list.vue'
import ruleAdd from '@/components/Admin/system/rule/add.vue'
import ruleEdit from '@/components/Admin/system/rule/edit.vue'
// 系统配置-系统配置项
import deployList from '@/components/Admin/system/deploy/list.vue'
import deployAdd from '@/components/Admin/system/deploy/add.vue'
import deployEdit from '@/components/Admin/system/deploy/edit.vue'
// 账户管理-角色管理
import groupsList from '@/components/Admin/personnel/groups/list.vue'
import groupsAdd from '@/components/Admin/personnel/groups/add.vue'
import groupsEdit from '@/components/Admin/personnel/groups/edit.vue'
// 账户管理-成员管理
import usersList from '@/components/Admin/personnel/users/list.vue'
import usersAdd from '@/components/Admin/personnel/users/add.vue'
import usersEdit from '@/components/Admin/personnel/users/edit.vue'
import mailList from '@/components/Busi/mail/mail/list.vue'
import mailAdd from '@/components/Busi/mail/mail/add.vue'
import mailEdit from '@/components/Busi/mail/mail/edit.vue'
// 账户管理-工作室管理
import studiosList from '@/components/Admin/personnel/studios/list.vue'
import studiosAdd from '@/components/Admin/personnel/studios/add.vue'
import studiosEdit from '@/components/Admin/personnel/studios/edit.vue'
// 账户管理-环节管理
import tachesList from '@/components/Admin/personnel/taches/list.vue'
import tachesAdd from '@/components/Admin/personnel/taches/add.vue'
import tachesEdit from '@/components/Admin/personnel/taches/edit.vue'
// 项目管理-项目
import projectsList from '@/components/Admin/project/projects/list.vue'
import projectsAdd from '@/components/Admin/project/projects/add.vue'
import projectsEdit from '@/components/Admin/project/projects/edit.vue'
// 工作台管理-工作台列表
import workbenchesList from '@/components/Admin/workbench/workbenches/list.vue'
// 镜头管理-镜头
import shotsList from '@/components/Admin/shot/shots/list.vue'
import shotsAdd from '@/components/Admin/shot/shots/add.vue'
import shotsListDetail from '@/components/Admin/shot/shots/list_detail.vue'
// 资产库管理 - 资产
import assetsList from '@/components/Admin/library/assets/list.vue'
import assetsAdd from '@/components/Admin/library/assets/add.vue'
// 库管理-参考库管理
import referencesList from '@/components/Admin/library/references/list.vue'
// 审批管理-审批管理
import approvalsList from '@/components/Admin/approval/approvals/list.vue'

Vue.use(VueRouter)

/**
 * meta参数解析
 * hideLeft: 是否隐藏左侧菜单，单页菜单为true
 * module: 菜单所属模块
 * menu: 所属菜单，用于判断三级菜单是否显示高亮，如菜单列表、添加菜单、编辑菜单都是'menu'，用户列表、添加用户、编辑用户都是'user'，如此类推
 */

const routes = [
  { path: '/', component: Login, name: 'Login' },
  {
    path: '/admin',
    component: Home,
    children: [
      { path: '/refresh', component: refresh, name: 'refresh' }
    ]
  },
  // 系统配置-菜单管理
  {
    path: '/admin',
    component: Home,
    children: [
      {
        path: 'menu/list',
        component: menuList,
        name: 'menuList',
        meta: { hideLeft: false, module: 'Admin', menu: 'menu' }
      },
      { path: 'menu/add', component: menuAdd, name: 'menuAdd', meta: { hideLeft: false, module: 'Admin', menu: 'menu' }},
      {
        path: 'menu/edit/:id',
        component: menuEdit,
        name: 'menuEdit',
        meta: { hideLeft: false, module: 'Admin', menu: 'menu' }
      }
    ]
  },
  // 系统配置-权限参数
  {
    path: '/admin',
    component: Home,
    children: [
      {
        path: 'config/add',
        component: systemConfig,
        name: 'systemConfig',
        meta: { hideLeft: false, module: 'Admin', menu: 'systemConfig' }
      }
    ]
  },
  // 系统配置-权限规则
  {
    path: '/admin',
    component: Home,
    children: [
      {
        path: 'rule/list',
        component: ruleList,
        name: 'ruleList',
        meta: { hideLeft: false, module: 'Admin', menu: 'rule' }
      },
      { path: 'rule/add', component: ruleAdd, name: 'ruleAdd', meta: { hideLeft: false, module: 'Admin', menu: 'rule' }},
      {
        path: 'rule/edit/:id',
        component: ruleEdit,
        name: 'ruleEdit',
        meta: { hideLeft: false, module: 'Admin', menu: 'rule' }
      }
    ]
  },
  // 系统配置-系统配置项
  {
    path: '/admin',
    component: Home,
    children: [
      {
        path: 'deploy/list',
        component: deployList,
        name: 'deployList',
        meta: { hideLeft: false, module: 'Admin', menu: 'deploy' }
      },
      { path: 'deploy/add', component: ruleAdd, name: 'deployAdd', meta: { hideLeft: false, module: 'Admin', menu: 'deploy' }},
      {
        path: 'deploy/edit/:id',
        component: deployEdit,
        name: 'deployEdit',
        meta: { hideLeft: false, module: 'Admin', menu: 'deploy' }
      }
    ]
  },
  // 账户管理-角色管理
  {
    path: '/admin',
    component: Home,
    children: [
      {
        path: 'groups/list',
        component: groupsList,
        name: 'groupsList',
        meta: { hideLeft: false, module: 'Admin', menu: 'groups' }
      },
      {
        path: 'groups/add',
        component: groupsAdd,
        name: 'groupsAdd',
        meta: { hideLeft: false, module: 'Admin', menu: 'groups' }
      },
      {
        path: 'groups/edit/:id',
        component: groupsEdit,
        name: 'groupsEdit',
        meta: { hideLeft: false, module: 'Admin', menu: 'groups' }
      }
    ]
  },
  // 账户管理-成员管理
  {
    path: '/admin',
    component: Home,
    children: [
      {
        path: 'users/list',
        component: usersList,
        name: 'usersList',
        meta: { hideLeft: false, module: 'Admin', menu: 'users' }
      },
      {
        path: 'users/add',
        component: usersAdd,
        name: 'usersAdd',
        meta: { hideLeft: false, module: 'Admin', menu: 'users' }
      },
      {
        path: 'users/edit/:id',
        component: usersEdit,
        name: 'usersEdit',
        meta: { hideLeft: false, module: 'Admin', menu: 'users' }
      }
    ]
  },
  {
    path: '/busi',
    component: Home,
    children: [
      { path: 'mail/list', component: mailList, name: 'mailList', meta: { hideLeft: false, module: 'Busi', menu: 'mail' }},
      { path: 'mail/add', component: mailAdd, name: 'mailAdd', meta: { hideLeft: false, module: 'Busi', menu: 'mail' }},
      {
        path: 'mail/edit/:id',
        component: mailEdit,
        name: 'mailEdit',
        meta: { hideLeft: false, module: 'Busi', menu: 'mail' }
      }
    ]
  },
  // 账户管理-工作室管理
  {
    path: '/admin',
    component: Home,
    children: [
      {
        path: 'studios/list',
        component: studiosList,
        name: 'studiosList',
        meta: { hideLeft: false, module: 'Admin', menu: 'studios' }
      },
      {
        path: 'studios/add',
        component: studiosAdd,
        name: 'studiosAdd',
        meta: { hideLeft: false, module: 'Admin', menu: 'studios' }
      },
      {
        path: 'studios/edit/:id',
        component: studiosEdit,
        name: 'studiosEdit',
        meta: { hideLeft: false, module: 'Admin', menu: 'studios' }
      }
    ]
  },
  // 账户管理-环节管理
  {
    path: '/admin',
    component: Home,
    children: [
      {
        path: 'taches/list',
        component: tachesList,
        name: 'tachesList',
        meta: { hideLeft: false, module: 'Admin', menu: 'taches' }
      },
      {
        path: 'taches/add',
        component: tachesAdd,
        name: 'tachesAdd',
        meta: { hideLeft: false, module: 'Admin', menu: 'taches' }
      },
      {
        path: 'taches/edit/:id',
        component: tachesEdit,
        name: 'tachesEdit',
        meta: { hideLeft: false, module: 'Admin', menu: 'taches' }
      }
    ]
  },
  // 项目管理-项目列表
  {
    path: '/admin',
    component: Home,
    children: [
      {
        path: 'projects/list',
        component: projectsList,
        name: 'projectsList',
        meta: { hideLeft: false, module: 'Admin', menu: 'projects' }
      },
      {
        path: 'projects/add',
        component: projectsAdd,
        name: 'projectsAdd',
        meta: { hideLeft: false, module: 'Admin', menu: 'projects' }
      },
      {
        path: 'projects/edit/:id',
        component: projectsEdit,
        name: 'projectsEdit',
        meta: { hideLeft: false, module: 'Admin', menu: 'projects' }
      }
    ]
  },
  // 工作台管理-工作台列表
  {
    path: '/admin',
    component: Home,
    children: [
      {
        path: 'workbenches/list',
        component: workbenchesList,
        name: 'workbenchesList',
        meta: { hideLeft: false, module: 'Admin', menu: 'workbenches' }
      }
    ]
  },
  // 镜头管理-镜头
  {
    path: '/admin',
    component: Home,
    children: [
      {
        path: 'shots/list',
        component: shotsList,
        name: 'shotsList',
        meta: { hideLeft: false, module: 'Admin', menu: 'shots' }
      },
      {
        path: 'shots/add',
        component: shotsAdd,
        name: 'shotsAdd',
        meta: { hideLeft: false, module: 'Admin', menu: 'shots' }
      },
      // {
      //   path: 'shots/edit/:id',
      //   component: shotsEdit,
      //   name: 'shotsEdit',
      //   meta: { hideLeft: false, module: 'Admin', menu: 'shots' }
      // },
      {
        path: 'shots/list_detail',
        component: shotsListDetail,
        name: 'shotsListDetail',
        meta: { hideLeft: false, module: 'Admin', menu: 'shots' }
      }
    ]
  },
  // 库管理-资产库
  {
    path: '/admin',
    component: Home,
    children: [
      {
        path: 'assets/list',
        component: assetsList,
        name: 'assetsList',
        meta: { hideLeft: false, module: 'Admin', menu: 'assets' }
      },
      {
        path: 'assets/add',
        component: assetsAdd,
        name: 'assetsAdd',
        meta: { hideLeft: false, module: 'Admin', menu: 'assets' }
      }
    ]
  },
  // 库管理-参考库管理
  {
    path: '/admin',
    component: Home,
    children: [
      {
        path: 'references/list',
        component: referencesList,
        name: 'referencesList',
        meta: { hideLeft: false, module: 'Admin', menu: 'references' }
      }
    ]
  },
  //审批管理-dailies
  {
    path: '/admin',
    component: Home,
    children: [
      {
        path: 'approvals/list',
        component: approvalsList,
        name: 'approvalsList',
        meta: { hideLeft: false, module: 'Admin', menu: 'approvals' }
      }
    ]
  }
]

const router = new VueRouter({
  mode: 'history',
  base: __dirname,
  routes
})
router.beforeEach(async (to, from, next) => {
  const hideLeft = to.meta.hideLeft
  store.dispatch('showLeftMenu', hideLeft)
  // 如果跳转去登录页
  if (to.name !== 'Login') {
    const expire = Lockr.get('expire')
    const advanceTime = config.advanceTime
    const nowTime = Math.floor(new Date().getTime() / 1000)
    const infos = baseHttp.apiPost('admin/infos/index')
    const quees = [infos]
    // if (nowTime >= (expire - advanceTime)) {
    const refresh = baseHttp.apiPost('admin/infos/refresh') // 获取新token
    quees.push(refresh)
    // }
    const result = await Promise.all(quees)
    // 如果请求多于1个（获取用户信息）
    if (result.length >= 1) {
      if (result[0].code === 200) {
        const data = result[0].data
        store.dispatch('setMenus', data.menusList)      // 菜单信息
        store.dispatch('setRules', data.authList)       // 权限信息
        store.dispatch('setUsers', data.userInfo)       // 用户信息
      } else {
        _g.toastMsg('warning', '请重新登录')
        setTimeout(() => {
          router.replace('/')
        }, 1500)
        return
      }
    }
    ;
    // 如果请求多于2个(获取用户信息，刷新token)
    if (result.length >= 2) {
      if (result[0].code === 200) {
        const data = result[1].data
        Lockr.set('authKey', data.authKey)              // 权限认证
        Lockr.set('expire', data.expire)              // 权限认证
      } else {
        _g.toastMsg('warning', '请重新登录')
        setTimeout(() => {
          router.replace('/')
        }, 1500)
        return
      }
    }
  }
  NProgress.start()
  next()
})

router.afterEach(transition => {
  NProgress.done()
})

export default router


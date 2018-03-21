const mutations = {
  showLeftMenu(state, status) {
    state.showLeftMenu = status
  },
  showLoading(state, status) {
    state.globalLoading = status
  },
  setMenus(state, menus) {
    state.menus = menus
  },
  setRules(state, rules) {
    state.rules = rules
  },
  setUsers(state, users) {
    state.users = users
  },
  setUserGroups(state, userGroups) {
    state.userGroups = userGroups
  },
  setOrganizes(state, organizes) {
    state.organizes = organizes
  },
  setGroups(state, groups) {
    state.groups = groups
  },
  setStudios(state, studios) {
    state.studios = studios
  },
  setTaches(state, taches) {
    state.taches = taches
  }
}

export default mutations

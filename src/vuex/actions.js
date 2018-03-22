const actions = {
  showLeftMenu ({ commit }, status) {
    commit('showLeftMenu', status)
  },
  showLoading ({ commit }, status) {
    commit('showLoading', status)
  },
  setMenus({ commit }, menus) {
    commit('setMenus', menus)
  },
  setRules({ commit }, rules) {
    commit('setRules', rules)
  },
  setUsers({ commit }, users) {
    commit('setUsers', users)
  },
  setUserGroups({ commit }, userGroups) {
    commit('setUserGroups', userGroups)
  },
  setOrganizes({ commit }, organizes) {
    commit('setOrganizes', organizes)
  },
  setGroups({ commit }, groups) {
    commit('setGroups', groups)
  },
  setStudios({ commit }, studios) {
    commit('setStudios', studios)
  },
  setTaches({ commit }, taches) {
    commit('setTaches', taches)
  }
}

export default actions

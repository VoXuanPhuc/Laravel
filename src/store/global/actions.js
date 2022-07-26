import { loadLanguageAsync } from "../../setups/i18n"

export default {
  addToastMessage({ commit }, { type, content, cb, delay = 10000, preventAutoClose = false }) {
    const key = new Date().getTime() + ~~(Math.random() * 100000)
    const obj = {}
    obj[key] = { type, content, key, cb }

    commit("addToastMessage", obj)
    // Automatically remove messages after a few seconds
    if (preventAutoClose === false) {
      const timeout = setTimeout(() => {
        commit("removeToastMessage", key)
        clearTimeout(timeout)
      }, delay)
    }
  },
  removeToastMessage({ commit }, payload) {
    commit("removeToastMessage", payload)
  },

  // For multi-tenants if needed
  setTenantId({ commit }, payload) {
    commit("setTenantId", payload)
  },

  // Set client id
  setClientId({ commit }, payload) {
    commit("setClientId", payload)
  },

  async setMe({ state, commit }, payload) {
    if (payload) {
      commit("setMe", payload)
    } else if (!state.me) {
      const result = {}
      commit("setMe", result)
    }
  },
  logout({ commit }) {
    commit("logout")
  },
  setLocale(_, locale) {
    loadLanguageAsync(locale)
  },
}

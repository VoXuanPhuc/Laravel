import router from "@/router"

export default {
  addToastMessage(state, payload) {
    state.messages = { ...state.messages, ...payload }
  },
  removeToastMessage(state, payload) {
    if (state.messages[payload]) {
      state.messages[payload].cb && state.messages[payload].cb()
      delete state.messages[payload]
      state.messages = { ...state.messages }
    }
  },
  setTenantSettings(state, payload) {
    state.tenantSettings = payload
  },
  setTenantId(state, payload) {
    state.tenantId = payload
    localStorage.setItem("readyBCAdminTenantId", payload)
  },
  setClientId(state, payload) {
    state.clientId = payload
    localStorage.setItem("readyBCAdminClientId", payload)
  },
  setMe(state, payload) {
    state.me = payload
  },
  setDateFormat(state, payload) {
    state.dateFormat = payload
  },
  setDateTimeFormat(state, payload) {
    state.dateTimeFormat = payload
  },
  logout(state) {
    const tenantId = state?.tenantId
    const clientId = state?.clientId
    if (clientId !== "escalate") {
      router.push(`/login?tenantId=${tenantId}&?clientId=${clientId}`)
    } else {
      router.push(`/login?tenantId=${tenantId}`)
    }
    // Clear token
    localStorage.removeItem(process.env.VUE_APP_TOKEN_KEY)
    localStorage.removeItem(process.env.VUE_APP_TOKEN_EXPIRES)
    state.me = null
  },
}

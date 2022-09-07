import dayjs from "dayjs"
import { defineStore } from "pinia"
import { useRouter } from "vue-router"
import { loadLanguageAsync } from "./../setups/i18nConfig"

export const useGlobalStore = defineStore("global", {
  state: () => ({
    tenantId: null,
    clientId: null,
    // Cross route messages for notifications
    messages: [],
    dateFormat: "DD/MM/YYYY",
    dateTimeFormat: "DD/MM/YYYY HH:mm:ss",
    tenantSettings: null,
    me: null,
    availableLocales: [],
  }),

  setup(props) {
    const router = useRouter()

    return {
      router,
    }
  },
  getters: {
    getTenantId() {
      return this.tenantId
    },

    getClientId() {
      return this.clientId
    },

    getMessages() {
      return this.messages
    },

    getTenantClientId() {
      return {
        tenantId: this.tenantId,
        clientId: this.clientId,
      }
    },

    getTenantSettings() {
      return this.tenantSettings
    },

    getDateTimeFormat() {
      return this.dateTimeFormat
    },
  },

  // Actions
  actions: {
    // Add Toast Message
    addToastMessage({ type, content, cb, delay = 10000, preventAutoClose = false }) {
      const key = new Date().getTime() + ~~(Math.random() * 100000)
      const obj = {}
      obj[key] = { type, content, key, cb }

      this.messages = { ...this.messages, ...obj }
      // Automatically remove messages after a few seconds
      if (preventAutoClose === false) {
        const timeout = setTimeout(() => {
          this.removeToastMessage(key)
          clearTimeout(timeout)
        }, delay)
      }
    },

    // Remove Toast message
    removeToastMessage(payload) {
      if (this.messages[payload]) {
        this.messages[payload].cb && this.messages[payload].cb()
        delete this.messages[payload]
        this.messages = { ...this.messages }
      }
    },

    /**
     *
     * @param {*} message
     */
    addSuccessToastMessage(message) {
      this.addToastMessage({ type: "success", content: message })
    },

    /**
     *
     * @param {*} message
     */
    addErrorToastMessage(message) {
      this.addToastMessage({ type: "error", content: message })
    },

    // Set Me
    async setMe(payload) {
      if (payload) {
        this.me = payload
      } else if (!this.me) {
        const result = {}
        this.me = result
      }
    },

    // For multi-tenants if needed
    setTenantId(payload) {
      this.tenantId = payload
      localStorage.setItem("readyBCAdminTenantId", payload)
    },

    // Set client id
    setClientId(payload) {
      this.clientId = payload
      localStorage.setItem("readyBCAdminClientId", payload)
    },

    // Set Tenant Settings
    setTenantSettings(payload) {
      this.tenantSettings = payload
    },

    setLocale(locale) {
      loadLanguageAsync(locale)
    },

    // Set setDateFormat
    setDateFormat(payload) {
      this.dateFormat = payload
    },

    // setDateTimeFormat
    setDateTimeFormat(payload) {
      this.dateTimeFormat = payload
    },

    /**
     *
     * @param {*} dateStr
     * @returns
     */
    formatDate(dateStr) {
      if (!dateStr) return "-"

      // If dayjs || Date object
      if (this.dateFormat) {
        const dayObj = dayjs(dateStr)
        if (dayObj.$d.toString() !== "Invalid Date") {
          return dayObj.format(this.dateFormat)
        }
      }
      return dateStr
    },

    /**
     *
     * @param {*} dateTimeStr
     * @returns
     */
    formatDateTime(dateTimeStr) {
      if (!dateTimeStr) return "-"

      // If dayjs || Date object
      if (this.dateFormat) {
        const dayObj = dayjs(dateTimeStr)
        if (dayObj.$d.toString() !== "Invalid Date") {
          return dayObj.format(this.dateTimeFormat)
        }
      }
      return dateTimeStr
    },

    // Logout
    logout() {
      const tenantId = this.tenantId
      const clientId = this.clientId

      if (clientId !== "escalate") {
        this.router.push(`/login?tenantId=${tenantId}&?clientId=${clientId}`)
      } else {
        this.router.push(`/login?tenantId=${tenantId}`)
      }
      // Clear token
      localStorage.removeItem(process.env.VUE_APP_TOKEN_KEY)
      localStorage.removeItem(process.env.VUE_APP_TOKEN_EXPIRES)
      this.me = null
    },
  }, // End Actions
})

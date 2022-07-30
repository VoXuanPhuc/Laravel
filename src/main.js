import { createApp } from "vue"
import App from "./App.vue"
import router from "@/router"
import store from "./store"
import { VuelidatePlugin } from "@vuelidate/core"
import "@/assets/css/tailwind.css"

// Get component variants
import { makeGetComponentVariants } from "./components/makeGetComponentVariants"

// Global components registration
import directivesRegistration from "./setups/directivesRegistration"
import globalComponentsRegistration from "./setups/globalComponentsRegistration"

// Function to initialize tenant
import { initializeTenant } from "./initialize"

// determine tenant base on domain or parametters
const queryString = window.location.search
const urlParams = new URLSearchParams(queryString)
const defaultTenant = "escalate"
const defaultClient = "escalate"

const tenantId = urlParams.get("tenantId") || localStorage.getItem("readyBCAdminTenantId") || defaultTenant
const clientId = urlParams.get("clientId") || localStorage.getItem("readyBCAdminClientId") || defaultClient

const app = createApp(App)

// we initialize tenant before mounting app
initializeTenant({ tenantId, clientId }).then(({ variants, i18n }) => {
  app.use(router)
  app.use(i18n)
  app.use(store)
  app.use(VuelidatePlugin)

  globalComponentsRegistration(app)
  directivesRegistration(app)

  // Provide getComponentVariants
  app.provide("getComponentVariants", makeGetComponentVariants({ variants }))

  // Finally mount app
  app.mount("#app")
})

export { app }

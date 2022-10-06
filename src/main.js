import { createApp } from "vue"
import App from "./App.vue"
import router from "@/router"
import pinia from "@/stores/pinia"
import useVuelidate from "@vuelidate/core"
import { VueCookies } from "vue-cookies"
import { checkAuthGuard } from "@/router/guards"
import "@/assets/css/tailwind.css"

// Get component variants
import { makeGetComponentVariants } from "@/components/makeGetComponentVariants"

import globalComponentsRegistration from "@/setups/globalComponentsRegistration"
// Global components registration
import directivesRegistration from "@/setups/directivesRegistration"

// Function to initialize tenant
import { initializeTenant } from "./initialize"

const app = createApp(App)

// Need to use Pinia first to avoid initializeTenant global store use error
app.use(pinia)
app.use(VueCookies, { expire: "3d" })

// Check auth guard
checkAuthGuard(router)
// Finally mount app

// we initialize tenant before mounting app
initializeTenant().then(({ variants, i18n }) => {
  app.use(router)
  app.use(i18n)

  app.use(useVuelidate({ $lazy: true, $autoDirty: true, $scope: true }))

  globalComponentsRegistration(app)
  directivesRegistration(app)

  // Provide getComponentVariants
  app.provide("getComponentVariants", makeGetComponentVariants({ variants }))

  app.mount("#app")
})

export { app }

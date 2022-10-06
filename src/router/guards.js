import dayjs from "dayjs"
import { useGlobalStore } from "@/stores/global"
import { goto } from "@/modules/core/composables"

const isAuthenticated = () => {
  const now = dayjs()
  const expireAt = dayjs(localStorage[process.env.VUE_APP_TOKEN_EXPIRES])

  return localStorage[process.env.VUE_APP_TOKEN_KEY] && now.isBefore(expireAt)
}

const setMe = async () => {
  const globalStore = useGlobalStore()
  try {
    await globalStore.setMe()
  } catch (error) {
    globalStore.addToastMessage({
      type: "error",
      content: {
        type: "message",
        text: error,
      },
    })
  }
}

const checkAuthGuard = (router) => {
  const globalStore = useGlobalStore()
  let currentTenantId = localStorage.getItem("readyBCAdminTenantId")
  let currentClientId = localStorage.getItem("readyBCAdminClientId")
  const blockedRoutes = ["ViewLogin", "ViewForgotPassword", "ViewNewPassword"]

  router.beforeEach(async (to, from, next) => {
    // If they enter the link which not allowed
    if (!globalStore.getAllowedModuleIDs?.includes(to.meta.module)) {
      console.log(to)
    }

    if (
      (to?.query?.tenantId && to?.query?.tenantId !== currentTenantId) ||
      (to?.query?.clientId && to?.query?.clientId !== currentClientId)
    ) {
      // if user enters tenant id or clientId that does not match current tenant and clientIdp
      globalStore.logout()
      goto("Dashboard")
      // tenantId and clientId in store will be the newly passed params
      // we assign above to currentTenantId and clientId to escape this loop
      currentTenantId = globalStore.getTenantId
      currentClientId = globalStore.getClientId
    } else if (!isAuthenticated() && !to.meta.isPublic) {
      // Store entered link into window.PATH
      // Navigate user to the page was stored after login (ViewLogin.vue)
      window.PATH = to.path
      next({ name: "ViewLogin" })
    } else if (isAuthenticated() && to.meta.isPublic && blockedRoutes.includes(to.name)) {
      // if user is authenticated && route is public && tries to visit one of blocked routes
      // go to root route (which is dashboard view by default)
      next({ name: "root" })
    } else {
      if (!to.meta.isPublic) await setMe()
      next()
    }
  })

  router.afterEach(async (to, from) => {
    // Set title
    document.title = to?.meta?.title || "Ready BC"
  })
}

export {
  // setupMockedAuthGuard,
  checkAuthGuard,
}

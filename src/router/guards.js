import dayjs from "dayjs"
import store from "@/store"

const isAuthenticated = () => {
  const now = dayjs()
  const expireAt = dayjs(localStorage[process.env.VUE_APP_TOKEN_EXPIRES])

  return localStorage[process.env.VUE_APP_TOKEN_KEY] && now.isBefore(expireAt)
}

const setMe = async () => {
  try {
    await store.dispatch("setMe")
  } catch (error) {
    store.dispatch("addToastMessage", {
      type: "error",
      content: {
        type: "message",
        text: error,
      },
    })
  }
}

const checkAuthGuard = (router) => {
  let currentTenantId = localStorage.getItem("readyBCAdminTenantId")
  let currentClientId = localStorage.getItem("readyBCAdminClientId")
  const blockedRoutes = ["ViewLogin", "ViewForgotPassword", "ViewNewPassword"]

  router.beforeEach(async (to, from, next) => {
    // if user enters tenant id or clientId that does not match current tenant and clientId
    if (
      (to?.query?.tenantId && to?.query?.tenantId !== currentTenantId) ||
      (to?.query?.clientId && to?.query?.clientId !== currentClientId)
    ) {
      store.dispatch("logout")
      // tenantId and clientId in store will be the newly passed params
      // we assign above to currentTenantId and clientId to escape this loop
      currentTenantId = store.state.tenantId
      currentClientId = store.state.clientId
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
}

export {
  // setupMockedAuthGuard,
  checkAuthGuard,
}

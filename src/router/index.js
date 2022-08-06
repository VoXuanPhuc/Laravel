import { createRouter, createWebHistory } from "vue-router"

import auth from "@/modules/auth/router/index"
import dashboard from "@/modules/dashboard/router/index"
import organization from "@/modules/organization/router/index"
import user from "@/modules/user/router/index"
import setting from "@/modules/setting/router/index"

const routes = [
  {
    path: "/",
    name: "root",
    redirect: "dashboard",
  },
  ...auth,
  ...dashboard,
  ...organization,
  ...user,
  ...setting,
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    return {
      top: 0,
      left: 0,
    }
  },
})

export default router

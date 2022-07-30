import { createRouter, createWebHistory } from "vue-router"
import { checkAuthGuard } from "./guards"
import auth from "@/modules/auth/router/index"
import dashboard from "@/modules/dashboard/router/index"

const routes = [
  {
    path: "/",
    name: "root",
    redirect: "dashboard",
  },
  ...auth,
  ...dashboard,
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

checkAuthGuard(router)

export default router

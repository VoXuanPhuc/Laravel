import { createRouter, createWebHistory } from "vue-router"
import { checkAuthGuard } from "./guards"
import auth from "@/modules/auth/router/index"

const routes = [
  {
    path: "/",
    name: "root",
    redirect: "dashboard",
  },
  ...auth,
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

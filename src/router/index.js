import ViewNotFound from "@/modules/core/views/ViewNotFound"
import { createRouter, createWebHistory } from "vue-router"

import core from "@/modules/core/router/index"
import auth from "@/modules/auth/router/index"
import dashboard from "@/modules/dashboard/router/index"
import organization from "@/modules/organization/router/index"
import activity from "@/modules/activity/router/index"
import resource from "@/modules/resource/router/index"
import industry from "@/modules/industry/router/index"
import user from "@/modules/user/router/index"
import setting from "@/modules/setting/router/index"
import supplier from "@/modules/supplier/router/index"
import dependency from "@/modules/dependency/router/index"
import businessContinuityPlan from "@/modules/bcp/router/index"
import assessment from "@/modules/assessment/router/index"
import notification from "@/modules/notification/router/index"

const routes = [
  {
    path: "/",
    name: "root",
    redirect: "dashboard",
  },
  ...core,
  ...auth,
  ...dashboard,
  ...organization,
  ...activity,
  ...resource,
  ...industry,
  ...user,
  ...setting,
  ...supplier,
  ...dependency,
  ...businessContinuityPlan,
  ...assessment,
  ...notification,

  {
    path: "/:catchAll(.*)",
    component: ViewNotFound,
    name: "ViewNotFound",
  },
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

import ViewDashboard from "../views/ViewDashboard.vue"
import ViewBrightDashboard from "../views/ViewBrightDashboard.vue"

export default [
  {
    path: "/dashboard",
    component: ViewDashboard,
    name: "ViewDashboard",
    props: true,
    meta: {
      title: "Dashboard",
      module: "dashboard",
    },
  },
  {
    path: "/bright-dashboard",
    component: ViewBrightDashboard,
    name: "ViewBrightDashboard",
    props: true,
    meta: {
      title: "Dashboard",
      module: "dashboard",
    },
  },
]

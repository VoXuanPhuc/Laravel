import ViewDashboard from "../views/ViewDashboard.vue"

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
]

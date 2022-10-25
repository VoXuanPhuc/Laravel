import ViewBCPList from "@/modules/bcp/views/ViewBCPList"
import ViewBCPNew from "@/modules/bcp/views/ViewBCPNew"
import ViewBCPDetail from "@/modules/bcp/views/ViewBCPDetail"

export default [
  {
    path: "/business-continuity-plans",
    component: ViewBCPList,
    name: "ViewBCPList",
    props: true,
    meta: {
      module: "business_continuity_plan",
    },
  },

  {
    path: "/business-continuity-plans/new",
    component: ViewBCPNew,
    name: "ViewBCPNew",
    props: true,
    meta: {
      isPublic: true,
      module: "business_continuity_plan",
    },
  },

  {
    path: "/business-continuity-plans/:uid",
    component: ViewBCPDetail,
    name: "ViewBCPDetail",
    props: true,
    meta: {
      isPublic: true,
      module: "business_continuity_plan",
    },
  },
]

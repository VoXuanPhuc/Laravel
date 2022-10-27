import ViewBIAList from "@/modules/assessment/views/ViewBIAList"
import ViewBIANew from "@/modules/assessment/views/ViewBIANew"
import ViewBIADetail from "@/modules/assessment/views/ViewBIADetail"

export default [
  {
    path: "/assessments",
    component: ViewBIAList,
    name: "ViewBIAList",
    props: true,
    meta: {
      module: "assessment",
    },
  },

  {
    path: "/assessments/new",
    component: ViewBIANew,
    name: "ViewBIANew",
    props: true,
    meta: {
      isPublic: true,
      module: "assessment",
    },
  },

  {
    path: "/assessments/:uid",
    component: ViewBIADetail,
    name: "ViewBIADetail",
    props: true,
    meta: {
      isPublic: true,
      module: "assessment",
    },
  },
]

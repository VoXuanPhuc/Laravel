import ViewIndustryList from "@/modules/industry/views/industries/ViewIndustryList"
import ViewIndustryDetail from "@/modules/industry/views/industries/ViewIndustryDetail"
import ViewIndustryNew from "@/modules/industry/views/industries/ViewIndustryNew"

export default [
  {
    path: "/industries",
    component: ViewIndustryList,
    name: "ViewIndustryList",
    props: true,
    meta: {
      module: "industry",
    },
  },
  {
    path: "/industries/:industryUid",
    component: ViewIndustryDetail,
    name: "ViewIndustryDetail",
    props: true,
    meta: {
      module: "industry",
    },
  },
  {
    path: "/industries/new",
    component: ViewIndustryNew,
    name: "AddIndustryNew",
    props: true,
    meta: {
      module: "industry",
    },
  },
]

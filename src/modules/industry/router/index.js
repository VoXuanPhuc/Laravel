import ViewIndustryList from "@/modules/industry/views/industries/ViewIndustryList"
import ViewIndustryDetail from "@/modules/industry/views/industries/ViewIndustryDetail"
import ViewIndustryNew from "@/modules/industry/views/industries/ViewIndustryNew"

export default [
  {
    path: "/industry",
    component: ViewIndustryList,
    name: "ViewIndustryList",
    props: true,
    meta: {
      module: "industry",
    },
  },
  {
    path: "/industry/:industryUid",
    component: ViewIndustryDetail,
    name: "ViewIndustryDetail",
    props: true,
    meta: {
      module: "industry",
    },
  },
  {
    path: "/industry/new",
    component: ViewIndustryNew,
    name: "AddIndustryNew",
    props: true,
    meta: {
      module: "industry",
    },
  },
]

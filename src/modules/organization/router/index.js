import ViewOrganizationList from "../views/ViewOrganizationList.vue"
import ViewOrganizationDetail from "../views/ViewOrganizationDetail.vue"
import ViewOrganizationNew from "../views/ViewOrganizationNew.vue"

export default [
  {
    path: "/organization",
    component: ViewOrganizationList,
    name: "ViewOrganizationList",
    props: true,
    meta: {
      module: "organization",
    },
  },
  {
    path: "/organization/:organizationId",
    component: ViewOrganizationDetail,
    name: "ViewOrganizationDetail",
    props: true,
    meta: {
      module: "organization",
    },
  },
  {
    path: "/organization/new",
    component: ViewOrganizationNew,
    name: "ViewOrganizationNew",
    props: true,
    meta: {
      module: "organization",
    },
  },
]

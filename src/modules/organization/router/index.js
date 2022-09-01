import ViewOrganizationList from "../views/organization/ViewOrganizationList.vue"
import ViewOrganizationDetail from "../views/organization/ViewOrganizationDetail.vue"
import ViewOrganizationNew from "../views/organization/ViewOrganizationNew.vue"
import ViewOrganizationManagement from "../views/organization/ViewOrganizationManagement.vue"

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
  {
    path: "/organization/management",
    component: ViewOrganizationManagement,
    name: "ViewOrganizationManagement",
    props: true,
    meta: {
      module: "organization",
    },
  },
]

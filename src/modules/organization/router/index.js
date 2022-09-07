import ViewOrganizationList from "../views/organization/ViewOrganizationList.vue"
import ViewOrganizationDetail from "../views/organization/ViewOrganizationDetail.vue"
import ViewOrganizationNew from "../views/organization/ViewOrganizationNew.vue"
import ViewOrganizationManagement from "../views/organization/ViewOrganizationManagement.vue"

// Division
import ViewDivisionNew from "../views/division/ViewDivisionNew.vue"
import ViewDivisionDetail from "../views/division/ViewDivisionDetail"

// BU
import ViewBusinessUnitList from "../views/business_units/ViewBusinessUnitList.vue"
import ViewDivisionBusinessUnitNew from "../views/business_units/ViewDivisionBusinessUnitNew.vue"
import ViewBusinessUnitNew from "../views/business_units/ViewBusinessUnitNew.vue"
import ViewBusinessUnitDetail from "../views/business_units/ViewBusinessUnitDetail.vue"

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
    path: "/organization/:organizationUid",
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
    path: "/organizations/:organizationUid",
    component: ViewOrganizationManagement,
    name: "ViewOrganizationManagement",
    props: true,
    meta: {
      module: "organization",
    },
  },

  // Divisions
  {
    path: "/organizations/:organizationUid/divisions",
    component: ViewDivisionNew,
    name: "ViewDivisionNew",
    props: true,
    meta: {
      module: "organization",
    },
  },
  {
    path: "/organizations/:organizationUid/divisions/:divisionUid",
    component: ViewDivisionDetail,
    name: "ViewDivisionDetail",
    props: true,
    meta: {
      module: "organization",
    },
  },

  // Business Unit
  {
    path: "/organizations/:organizationUid/business-units",
    component: ViewBusinessUnitNew,
    name: "ViewBusinessUnitNew",
    props: true,
    meta: {
      module: "organization",
    },
  },

  // Division new BU
  {
    path: "/organizations/:organizationUid/divisions/:divisionUid/business-units/new",
    component: ViewDivisionBusinessUnitNew,
    name: "ViewDivisionBusinessUnitNew",
    props: true,
    meta: {
      module: "organization",
    },
  },

  // List BU
  {
    path: "/organizations/:organizationUid/divisions/:divisionUid/business-units",
    component: ViewBusinessUnitList,
    name: "ViewBusinessUnitList",
    props: true,
    meta: {
      module: "organization",
    },
  },
  {
    path: "/organizations/:organizationUid/divisions/:divisionUid/business-units/:businessUnitUid",
    component: ViewBusinessUnitDetail,
    name: "ViewBusinessUnitDetail",
    props: true,
    meta: {
      module: "organization",
    },
  },
]

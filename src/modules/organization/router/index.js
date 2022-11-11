import ViewOrganizationList from "@/modules/organization/views/organization/ViewOrganizationList.vue"
import ViewOrganizationDetail from "@/modules/organization/views/organization/ViewOrganizationDetail.vue"
import ViewOrganizationNew from "@/modules/organization/views/organization/ViewOrganizationNew.vue"

// Departments
import ViewDepartmentManagement from "@/modules/organization/views/department/ViewDepartmentManagement.vue"

// Division
import ViewDivisionNew from "@/modules/organization/views/division/ViewDivisionNew.vue"
import ViewDivisionDetail from "@/modules/organization/views/division/ViewDivisionDetail"

// BU
import ViewBusinessUnitList from "@/modules/organization/views/business_units/ViewBusinessUnitList.vue"
import ViewDivisionBusinessUnitNew from "@/modules/organization/views/business_units/ViewDivisionBusinessUnitNew.vue"
import ViewBusinessUnitNew from "@/modules/organization/views/business_units/ViewBusinessUnitNew.vue"
import ViewBusinessUnitDetail from "@/modules/organization/views/business_units/ViewBusinessUnitDetail.vue"

export default [
  /**
   * ADMIN
   */
  {
    path: "/organization",
    component: ViewOrganizationList,
    name: "ViewOrganizationList",
    props: true,
    meta: {
      title: "Organization",
      module: "organization",
    },
  },
  {
    path: "/organization/:uid",
    component: ViewOrganizationDetail,
    name: "ViewOrganizationDetail",
    props: true,
    meta: {
      title: "Organization Detail",
      module: "organization",
      landlordAccess: true,
    },
  },
  {
    path: "/organization/new",
    component: ViewOrganizationNew,
    name: "ViewOrganizationNew",
    props: true,
    meta: {
      title: "New Organization",
      module: "organization",
    },
  },
  {
    path: "/organizations/:organizationUid",
    component: ViewDepartmentManagement,
    name: "ViewOrganizationManagement",
    props: true,
    meta: {
      title: "Manage Organization",
      module: "organization",
      landlordAccess: true,
    },
  },

  /**
   * TENANT
   */

  // Department
  {
    path: "/departments",
    component: ViewDepartmentManagement,
    name: "ViewDepartmentManagement",
    props: true,
    meta: {
      title: "Departments",
      module: "department",
      landlordAccess: true,
    },
  },
  // Divisions
  {
    path: "/departments/divisions/new",
    component: ViewDivisionNew,
    name: "ViewDivisionNew",
    props: true,
    meta: {
      title: "New Division",
      module: "department",
      landlordAccess: true,
    },
  },
  {
    path: "/departments/divisions/:divisionUid",
    component: ViewDivisionDetail,
    name: "ViewDivisionDetail",
    props: true,
    meta: {
      title: "Division Detail",
      module: "department",
      landlordAccess: true,
    },
  },

  // Business Unit
  {
    path: "/departments/business-units",
    component: ViewBusinessUnitNew,
    name: "ViewBusinessUnitNew",
    props: true,
    meta: {
      title: "New Business Unit",
      module: "department",
      landlordAccess: true,
    },
  },

  // Division new BU
  {
    path: "/departments/business-units/new",
    component: ViewDivisionBusinessUnitNew,
    name: "ViewDivisionBusinessUnitNew",
    props: true,
    meta: {
      title: "New Business Unit",
      module: "department",
      landlordAccess: true,
    },
  },

  // List BU
  {
    path: "/departments/divisions/:divisionUid/business-units",
    component: ViewBusinessUnitList,
    name: "ViewBusinessUnitList",
    props: true,
    meta: {
      title: "Business Units",
      module: "department",
      landlordAccess: true,
    },
  },
  {
    path: "/departments/business-units/:businessUnitUid",
    component: ViewBusinessUnitDetail,
    name: "ViewBusinessUnitDetail",
    props: true,
    meta: {
      title: "Business Unit Detail",
      module: "department",
      landlordAccess: true,
    },
  },
]

import ViewSupplierList from "@/modules/supplier/views/supplier/ViewSupplierList"
import ViewSupplierNew from "@/modules/supplier/views/supplier/ViewSupplierNew"
import ViewSupplierDetail from "@/modules/supplier/views/supplier/ViewSupplierDetail"
import ViewCategoryList from "@/modules/supplier/views/category/ViewCategoryList"
import ViewCategoryDetail from "@/modules/supplier/views/category/ViewCategoryDetail"

export default [
  {
    path: "/suppliers",
    component: ViewSupplierList,
    name: "ViewSupplierList",
    props: true,
    meta: {
      module: "supplier",
    },
  },

  {
    path: "/suppliers/new",
    component: ViewSupplierNew,
    name: "ViewSupplierNew",
    props: true,
    meta: {
      module: "supplier",
    },
  },

  {
    path: "/suppliers/:uid",
    component: ViewSupplierDetail,
    name: "ViewSupplierDetail",
    props: true,
    meta: {
      module: "supplier",
    },
  },
  // ============ CATEGORIES =========
  {
    path: "/supplier-categories",
    component: ViewCategoryList,
    name: "ViewSupplierCategoryList",
    props: true,
    meta: {
      module: "supplier",
    },
  },

  {
    path: "/supplier-categories/:uid",
    component: ViewCategoryDetail,
    name: "ViewSupplierCategoryDetail",
    props: true,
    meta: {
      isPublic: true,
      module: "supplier",
    },
  },
]

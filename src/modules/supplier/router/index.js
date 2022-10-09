import ViewSupplierList from "@/modules/supplier/views/ViewSupplierList"
import ViewSupplierNew from "@/modules/supplier/views/ViewSupplierNew"
import ViewSupplierDetail from "@/modules/supplier/views/ViewSupplierDetail"

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
]

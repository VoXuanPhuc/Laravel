import ViewResourceList from "../views/ViewResourceList"
import ViewResourceNew from "../views/ViewResourceNew"
import ViewResourceDetail from "../views/ViewResourceDetail"

export default [
  {
    path: "/resources",
    component: ViewResourceList,
    name: "ViewResourceList",
    props: true,
    meta: {
      module: "resource",
    },
  },

  {
    path: "/resources/new",
    component: ViewResourceNew,
    name: "ViewResourceNew",
    props: true,
    meta: {
      isPublic: true,
      module: "resource",
    },
  },

  {
    path: "/resources/:uid",
    component: ViewResourceDetail,
    name: "ViewResourceDetail",
    props: true,
    meta: {
      isPublic: true,
      module: "resource",
    },
  },
]

import ViewResourceList from "../views/resource/ViewResourceList"
import ViewResourceNew from "../views/resource/ViewResourceNew"
import ViewResourceDetail from "../views/resource/ViewResourceDetail"

// == Owners ==
import ViewOwnerList from "../views/owner/ViewOwnerList"
import ViewOwnerNew from "../views/owner/ViewOwnerNew"
import ViewOwnerDetail from "../views/owner/ViewOwnerDetail"

// == Categories ==
import ViewCategoryList from "../views/category/ViewCategoryList"
import ViewCategoryDetail from "../views/category/ViewCategoryDetail"

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

  // ============ OWNERS =========
  {
    path: "/resource-owners",
    component: ViewOwnerList,
    name: "ViewOwnerList",
    props: true,
    meta: {
      module: "resource",
    },
  },

  {
    path: "/resource-owners/new",
    component: ViewOwnerNew,
    name: "ViewOwnerNew",
    props: true,
    meta: {
      isPublic: true,
      module: "resource",
    },
  },

  {
    path: "/resource-owners/:uid",
    component: ViewOwnerDetail,
    name: "ViewOwnerDetail",
    props: true,
    meta: {
      isPublic: true,
      module: "resource",
    },
  },

  // ============ CATEGORIES =========
  {
    path: "/resource-categories",
    component: ViewCategoryList,
    name: "ViewCategoryList",
    props: true,
    meta: {
      module: "resource",
    },
  },

  {
    path: "/resource-categories/:uid",
    component: ViewCategoryDetail,
    name: "ViewCategoryDetail",
    props: true,
    meta: {
      isPublic: true,
      module: "resource",
    },
  },
]

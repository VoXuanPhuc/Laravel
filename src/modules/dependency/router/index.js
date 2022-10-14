import ViewDependencyList from "@/modules/dependency/views/dependency/ViewDependencyList"
import ViewDependencyNew from "@/modules/dependency/views/dependency/ViewDependencyNew"
import ViewDependencyDetail from "@/modules/dependency/views/dependency/ViewDependencyDetail"

// == Owners ==
import ViewOwnerList from "@/modules/dependency/views/owner/ViewOwnerList"
import ViewOwnerNew from "@/modules/dependency/views/owner/ViewOwnerNew"
import ViewOwnerDetail from "@/modules/dependency/views/owner/ViewOwnerDetail"

// == Categories ==
import ViewCategoryList from "@/modules/dependency/views/category/ViewCategoryList"
import ViewCategoryDetail from "@/modules/dependency/views/category/ViewCategoryDetail"

export default [
  {
    path: "/dependencies",
    component: ViewDependencyList,
    name: "ViewDependencyList",
    props: true,
    meta: {
      module: "dependency",
    },
  },

  {
    path: "/dependencies/new",
    component: ViewDependencyNew,
    name: "ViewDependencyNew",
    props: true,
    meta: {
      isPublic: true,
      module: "dependency",
    },
  },

  {
    path: "/dependencies/:uid",
    component: ViewDependencyDetail,
    name: "ViewDependencyDetail",
    props: true,
    meta: {
      isPublic: true,
      module: "dependency",
    },
  },

  // ============ OWNERS =========
  {
    path: "/resource-owners",
    component: ViewOwnerList,
    name: "ViewOwnerList",
    props: true,
    meta: {
      module: "dependency",
    },
  },

  {
    path: "/resource-owners/new",
    component: ViewOwnerNew,
    name: "ViewOwnerNew",
    props: true,
    meta: {
      isPublic: true,
      module: "dependency",
    },
  },

  {
    path: "/resource-owners/:uid",
    component: ViewOwnerDetail,
    name: "ViewOwnerDetail",
    props: true,
    meta: {
      isPublic: true,
      module: "dependency",
    },
  },

  // ============ CATEGORIES =========
  {
    path: "/resource-categories",
    component: ViewCategoryList,
    name: "ViewCategoryList",
    props: true,
    meta: {
      module: "dependency",
    },
  },

  {
    path: "/resource-categories/:uid",
    component: ViewCategoryDetail,
    name: "ViewCategoryDetail",
    props: true,
    meta: {
      isPublic: true,
      module: "dependency",
    },
  },
]

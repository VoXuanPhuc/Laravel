import ViewUserList from "../views/ViewUserList.vue"
import ViewUserNew from "../views/ViewUserNew.vue"
import ViewUserDetail from "../views/ViewUserDetail.vue"
import ViewRoles from "../views/ViewRoles.vue"
import ViewRoleDetail from "../views/ViewRoleDetail.vue"
import ViewRoleNew from "../views/ViewRoleNew.vue"

export default [
  {
    path: "/users/manage-users",
    component: ViewUserList,
    name: "ViewUserList",
    props: true,
    meta: {
      isPublic: true,
      module: "user",
    },
  },
  {
    path: "/users/manage-users/new",
    component: ViewUserNew,
    name: "ViewUserNew",
    props: true,
    meta: {
      isPublic: true,
      module: "user",
    },
  },
  {
    path: "/users/manage-users/:userId",
    component: ViewUserDetail,
    name: "ViewUserDetail",
    props: true,
    meta: {
      isPublic: true,
      module: "user",
    },
  },
  {
    path: "/users/roles",
    component: ViewRoles,
    name: "ViewRoles",
    props: true,
    meta: {
      module: "user",
    },
  },
  {
    path: "/users/roles/new",
    component: ViewRoleNew,
    name: "ViewRoleNew",
    props: true,
    meta: {
      module: "user",
    },
  },
  {
    path: "/users/roles/:uid",
    component: ViewRoleDetail,
    name: "ViewRoleDetail",
    props: true,
    meta: {
      module: "user",
    },
  },
]

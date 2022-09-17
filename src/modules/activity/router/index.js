import ViewActivityList from "../views/ViewActivityList"
import ViewActivityNew from "../views/ViewActivityNew"
import ViewActivityDetail from "../views/ViewActivityDetail"
import ViewActivityRemoteAccess from "../views/ViewActivityRemoteAccess"
import ViewActivityApplication from "../views/ViewActivityApplication"

export default [
  {
    path: "/orgainizations/:organizationUid/activities",
    component: ViewActivityList,
    name: "ViewOrganizationActivityList",
    props: true,
    meta: {
      module: "activity",
    },
  },
  {
    path: "/activities",
    component: ViewActivityList,
    name: "ViewActivityList",
    props: true,
    meta: {
      module: "activity",
    },
  },

  {
    path: "/activities/new",
    component: ViewActivityNew,
    name: "ViewActivityNew",
    props: true,
    meta: {
      isPublic: true,
      module: "activity",
    },
  },

  {
    path: "/activities/:uid",
    component: ViewActivityDetail,
    name: "ViewActivityDetail",
    props: true,
    meta: {
      isPublic: true,
      module: "activity",
    },
  },

  {
    path: "/activities/:uid/remote-accesses",
    component: ViewActivityRemoteAccess,
    name: "ViewActivityRemoteAccess",
    props: true,
    meta: {
      isPublic: true,
      module: "activity",
    },
  },

  {
    path: "/activities/:uid/applications",
    component: ViewActivityApplication,
    name: "ViewActivityApplication",
    props: true,
    meta: {
      module: "activity",
      isPublic: true,
    },
  },
]

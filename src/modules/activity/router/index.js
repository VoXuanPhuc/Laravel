import ViewActivityList from "../views/ViewActivityList"
import ViewActivityNew from "../views/ViewActivityNew"
import ViewActivityDetail from "../views/ViewActivityDetail"
import ViewActivityRemoteAccess from "../views/ViewActivityRemoteAccess"
import ViewActivityApplication from "../views/ViewActivityApplication"
import ViewActivityUpdateRemoteAccess from "../views/ViewActivityUpdateRemoteAccess"
import ViewActivityUpdateApplication from "../views/ViewActivityUpdateApplication"

export default [
  {
    path: "/organizations/first-organization/activities",
    component: ViewActivityList,
    name: "ViewActivityList",
    props: true,
    meta: {
      module: "activity",
    },
  },
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
    path: "/activities/new/:uid",
    component: ViewActivityNew,
    name: "ViewActivityNewBack",
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

  // For create new

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

  // For update

  {
    path: "/activities/:uid/update-remote-accesses",
    component: ViewActivityUpdateRemoteAccess,
    name: "ViewActivityUpdateRemoteAccess",
    props: true,
    meta: {
      isPublic: true,
      module: "activity",
    },
  },

  {
    path: "/activities/:uid/update-applications",
    component: ViewActivityUpdateApplication,
    name: "ViewActivityUpdateApplication",
    props: true,
    meta: {
      module: "activity",
      isPublic: true,
    },
  },
]

import ViewActivityList from "@/modules/activity/views/ViewActivityList"
import ViewActivityNew from "@/modules/activity/views/ViewActivityNew"
import ViewActivityDetail from "@/modules/activity/views/ViewActivityDetail"
import ViewActivityRemoteAccess from "@/modules/activity/views/ViewActivityRemoteAccess"
import ViewActivityApplication from "@/modules/activity/views/ViewActivityApplication"
import ViewActivityUpdateRemoteAccess from "@/modules/activity/views/ViewActivityUpdateRemoteAccess"
import ViewActivityUpdateApplication from "@/modules/activity/views/ViewActivityUpdateApplication"

export default [
  {
    path: "/activities",
    component: ViewActivityList,
    name: "ViewActivityList",
    props: true,
    meta: {
      title: "Activity List",
      module: "activity",
      landlordAccess: true,
    },
  },

  {
    path: "/activities/new",
    component: ViewActivityNew,
    name: "ViewActivityNew",
    props: true,
    meta: {
      title: "New Activity",
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
      title: "New Activity",
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
      title: "Edit Activity",
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
      title: "Activity Remote Access Factor",
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
      title: "Activity Applications and Equipments",
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
      title: "Update Activity Remote Access Factor",
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
      title: "Update Activity Applications and Equipments",
      module: "activity",
      isPublic: true,
    },
  },
]

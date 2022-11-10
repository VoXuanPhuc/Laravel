import ViewActivityList from "@/modules/activity/views/ViewActivityList"
import ViewActivityNew from "@/modules/activity/views/ViewActivityNew"
import ViewActivityDetail from "@/modules/activity/views/ViewActivityDetail"
import ViewActivityRemoteAccess from "@/modules/activity/views/ViewActivityRemoteAccess"
import ViewActivityApplication from "@/modules/activity/views/ViewActivityApplication"
import ViewActivityTolerant from "@/modules/activity/views/ViewActivityTolerant"
import ViewActivityRTO from "@/modules/activity/views/ViewActivityRTO"
import ViewActivityDependencies from "@/modules/activity/views/ViewActivityDependencies"

// Update
import ViewActivityUpdateRemoteAccess from "@/modules/activity/views/ViewActivityUpdateRemoteAccess"
import ViewActivityUpdateApplication from "@/modules/activity/views/ViewActivityUpdateApplication"
import ViewActivityUpdateTolerant from "@/modules/activity/views/ViewActivityUpdateTolerant"
import ViewActivityUpdateRTO from "@/modules/activity/views/ViewActivityUpdateRTO"
import ViewActivityUpdateDependencies from "@/modules/activity/views/ViewActivityUpdateDependencies"

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

  {
    path: "/activities/:uid/tolerant",
    component: ViewActivityTolerant,
    name: "ViewActivityTolerant",
    props: true,
    meta: {
      title: "Activity Tolerant",
      module: "activity",
      isPublic: true,
    },
  },
  {
    path: "/activities/:uid/recovery-time",
    component: ViewActivityRTO,
    name: "ViewActivityRTO",
    props: true,
    meta: {
      title: "Activity Recovery Time Objective",
      module: "activity",
      isPublic: true,
    },
  },

  {
    path: "/activities/:uid/dependencies",
    component: ViewActivityDependencies,
    name: "ViewActivityDependencies",
    props: true,
    meta: {
      title: "Internal Dependencies And Critical Suppliers",
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

  {
    path: "/activities/:uid/update-tolerant",
    component: ViewActivityUpdateTolerant,
    name: "ViewActivityUpdateTolerant",
    props: true,
    meta: {
      title: "Update Activity Tolerant",
      module: "activity",
      isPublic: true,
    },
  },

  {
    path: "/activities/:uid/update-recovery-time",
    component: ViewActivityUpdateRTO,
    name: "ViewActivityUpdateRTO",
    props: true,
    meta: {
      title: "Update Activity RTO",
      module: "activity",
      isPublic: true,
    },
  },

  {
    path: "/activities/:uid/update-dependencies",
    component: ViewActivityUpdateDependencies,
    name: "ViewUpdateActivityDependencies",
    props: true,
    meta: {
      title: "Update Internal Dependencies And Critical Suppliers",
      module: "activity",
      isPublic: true,
    },
  },
]

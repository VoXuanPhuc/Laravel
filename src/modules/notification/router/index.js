import ViewEventNotificationList from "@/modules/notification/views/noti/ViewEventNotificationList"
import ViewEventNotificationNew from "@/modules/notification/views/noti/ViewEventNotificationNew"
import ViewEventNotificationDetail from "@/modules/notification/views/noti/ViewEventNotificationDetail"

// Logs
import ViewNotificationLog from "@/modules/notification/views/log/ViewNotificationLog"
import ViewNotificationLogDetail from "@/modules/notification/views/log/ViewNotificationLogDetail"

// Managed template
import ViewManagedTemplateList from "@/modules/notification/views/template/ViewManagedTemplateList"
import ViewManagedTemplateNew from "@/modules/notification/views/template/ViewManagedTemplateNew"
import ViewManagedTemplateDetail from "@/modules/notification/views/template/ViewManagedTemplateDetail"

export default [
  {
    path: "/event-notifications",
    component: ViewEventNotificationList,
    name: "ViewEventNotificationList",
    props: true,
    meta: {
      module: "settings",
    },
  },
  {
    path: "/event-notifications/new",
    component: ViewEventNotificationNew,
    name: "ViewEventNotificationNew",
    props: true,
    meta: {
      module: "settings",
    },
  },
  {
    path: "/event-notifications/:uid",
    component: ViewEventNotificationDetail,
    name: "ViewEventNotificationDetail",
    props: true,
    meta: {
      module: "settings",
    },
  },

  // Logs
  {
    path: "/notifications/logs",
    component: ViewNotificationLog,
    name: "ViewNotificationLog",
    props: true,
    meta: {
      module: "notification",
    },
  },
  {
    path: "/notifications/logs/:uid",
    component: ViewNotificationLogDetail,
    name: "ViewNotificationLogDetail",
    props: true,
    meta: {
      module: "notification",
    },
  },

  // Managed Tempate
  {
    path: "/notifications/templates",
    component: ViewManagedTemplateList,
    name: "ViewManagedTemplateList",
    props: true,
    meta: {
      module: "notification",
    },
  },
  {
    path: "/notifications/templates/new",
    component: ViewManagedTemplateNew,
    name: "ViewManagedTemplateNew",
    props: true,
    meta: {
      module: "notification",
    },
  },
  {
    path: "/notifications/templates/:uid",
    component: ViewManagedTemplateDetail,
    name: "ViewManagedTemplateDetail",
    props: true,
    meta: {
      module: "notification",
    },
  },
]

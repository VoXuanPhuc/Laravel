import ViewEventNotificationList from "@/modules/notification/views/noti/ViewEventNotificationList"
import ViewEventNotificationNew from "@/modules/notification/views/noti/ViewEventNotificationNew"
import ViewEventNotificationDetail from "@/modules/notification/views/noti/ViewEventNotificationDetail"

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
]

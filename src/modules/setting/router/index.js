import ViewSettingList from "@/modules/setting/views/ViewSettingList"
import ViewGeneralSetting from "@/modules/setting/views/general/ViewGeneralSetting"

import ViewMailSetting from "@/modules/setting/views/mail/ViewMailSetting"
import ViewProfile from "@/modules/setting/views/profile/ViewProfile"

export default [
  {
    path: "/settings",
    component: ViewSettingList,
    name: "ViewSettingList",
    props: true,
    meta: {
      module: "settings",
    },
  },
  {
    path: "/settings/general",
    component: ViewGeneralSetting,
    name: "ViewGeneralSetting",
    props: true,
    meta: {
      module: "settings",
    },
  },

  {
    path: "/settings/mail",
    component: ViewMailSetting,
    name: "ViewMailSetting",
    props: true,
    meta: {
      module: "settings",
    },
  },
  {
    path: "/profile",
    component: ViewProfile,
    name: "ViewProfile",
    props: true,
    meta: {
      module: "settings",
    },
  },
]

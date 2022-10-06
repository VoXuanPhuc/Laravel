import ViewSettingList from "@/modules/setting/views/ViewSettingList"

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
]

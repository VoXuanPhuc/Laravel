export function useSetting() {
  const settingItems = [
    {
      key: "general",
      label: "setting.labels.general",
      icon: "Cog",
      routeName: "ViewGeneralSetting",
    },
    {
      key: "eventNoti",
      label: "setting.labels.eventNoti",
      icon: "Bell",
      routeName: "ViewEventNotificationList",
    },
    {
      key: "notiTemplates",
      label: "setting.labels.notificationTemplates",
      icon: "Template",
      routeName: "ViewNotificationTemplateList",
    },
    {
      key: "mail",
      label: "setting.labels.mail",
      icon: "Mail",
      routeName: "ViewMailSetting",
    },
  ]

  return {
    settingItems,
  }
}

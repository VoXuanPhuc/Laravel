<template>
  <RTabs class="mt-4 md:mt-6" :options="subMenuOptions" :activeTab="activeTab" @change="onSubmenuChange($event)" />
</template>

<script>
import { computed } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useI18n } from "vue-i18n"
export default {
  name: "EventNotificationSubMenu",
  setup() {
    const { t } = useI18n()
    const route = useRoute()
    const router = useRouter()

    const subMenuOptions = [
      {
        name: "ViewEventNotificationList",
        label: t("notification.tabs.manageEventNotifications"),
        routeName: "ViewEventNotificationList",
      },
      {
        name: "ViewNotificationLog",
        label: t("notification.tabs.notificationLogs"),
        routeName: "ViewNotificationLog",
      },
    ]

    const activeTab = computed(() => route.name)

    const onSubmenuChange = (payload) => {
      router.push({ name: payload?.routeName })
    }

    return { subMenuOptions, activeTab, onSubmenuChange }
  },
}
</script>

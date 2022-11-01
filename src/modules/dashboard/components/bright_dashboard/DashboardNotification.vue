<template>
  <!-- Title -->
  <EcFlex class="items-center">
    <EcButton variant="transparent" @click="handleToggleNotiSidebar">
      <EcIcon :icon="toggleIcon" width="48" height="48" />
    </EcButton>
    <EcLabel v-if="isNotiSidebarOpened" class="text-xl font-bold ml-2">Notifications</EcLabel>
  </EcFlex>

  <!-- List notifications -->
  <EcBox v-if="isNotiSidebarOpened" class="ml-2">
    <NotificationItemSkeleton :loading="isLoading" :rows="3">
      <!-- Pinned -->
      <NotificationItem
        v-for="notification in notifications.pinnedNotifications"
        :key="notification.uid"
        :notification="notification"
        @callbackAfterMarkAsRead="handleAfterMarkNotificationAsRead"
      />

      <!-- End Pinned Notification -->

      <!-- Newest Notification -->
      <NotificationItem
        v-for="notification in notifications.newestNotifications"
        :key="notification.uid"
        :notification="notification"
        @callbackAfterMarkAsRead="handleAfterMarkNotificationAsRead"
      />
      <!-- End Newest Notification -->

      <!-- Unread -->
      <EcFlex class="rounded-3xl border border-c3-50 p-4 mt-6">
        <EcBox>
          <EcIcon icon="NotiMessage" width="64" height="64" />
        </EcBox>

        <EcBox class="ml-3">
          <EcLabel class="font-semibold">Unread Notifications</EcLabel>
          <EcText class="mt-3">
            You have <strong> {{ notifications?.unreadCount || 0 }}</strong> unread notification(s)
          </EcText>
          <EcText class="mt-3 text-c3-100"></EcText>
        </EcBox>
      </EcFlex>
    </NotificationItemSkeleton>

    <!-- End notification -->
  </EcBox>
</template>

<script>
import NotificationItemSkeleton from "./NotificationItemSkeleton.vue"
import { useDashboardNotification } from "../../use/useDashboardNotification"
import { ref } from "vue"

import NotificationItem from "./NotificationItem.vue"
import useBrightDashboard from "../../stores/useBrightDashboard"

export default {
  name: "DashboardNotification",
  components: { NotificationItemSkeleton, NotificationItem },

  data() {
    return {
      isLoading: false,
      notifications: ref({}),
      processingNotifications: {},
    }
  },
  setup() {
    const { getDashboardNotifications } = useDashboardNotification()
    const brightDashboardStore = useBrightDashboard()

    return {
      getDashboardNotifications,
      brightDashboardStore,
    }
  },
  computed: {
    toggleIcon() {
      return this.brightDashboardStore.notiSidebarOpened ? "NotiExpanded" : "NotiCollapsed"
    },
    isNotiSidebarOpened() {
      return this.brightDashboardStore.notiSidebarOpened
    },
  },

  mounted() {
    this.fetchNotifications()
  },
  methods: {
    /**
     *
     * @param {*} value
     */
    async fetchNotifications() {
      this.isLoading = true

      const data = await this.getDashboardNotifications()

      if (data) {
        this.notifications = data
        this.isLoading = false
      }
    },

    handleToggleNotiSidebar() {
      this.brightDashboardStore.notiSidebarOpened = !this.brightDashboardStore.notiSidebarOpened
    },

    /**
     * After mark as read, re-pull notifications
     */
    handleAfterMarkNotificationAsRead() {
      this.fetchNotifications()
    },
  },
}
</script>

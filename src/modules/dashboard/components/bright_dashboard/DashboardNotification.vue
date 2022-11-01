<template>
  <!-- Title -->
  <EcFlex class="items-center">
    <EcButton variant="transparent">
      <EcIcon icon="NotiCollapse" width="48" height="48" />
    </EcButton>
    <EcLabel class="text-xl font-bold ml-2">Notifications</EcLabel>
  </EcFlex>

  <!-- List notifications -->
  <EcBox class="ml-2">
    <NotificationItemSkeleton :loading="isLoading" :rows="3">
      <!-- Pinned -->
      <EcFlex
        v-for="notification in notifications.pinnedNotifications"
        :key="notification.uid"
        class="rounded-3xl border border-c3-300 p-4 mt-8"
      >
        <EcBox>
          <EcIcon icon="NotiPinned" width="64" height="64" />
        </EcBox>
        <EcBox class="ml-3">
          <EcLabel class="font-semibold">{{ notification?.title }}</EcLabel>
          <EcText class="mt-3 text-c3-100"> {{ notification?.data }} </EcText>
          <EcText class="mt-3 text-c3-100">{{ notification?.time }}</EcText>
        </EcBox>
      </EcFlex>

      <!-- End Pinned Notification -->

      <!-- Newest Notification -->
      <EcFlex
        v-for="notification in notifications.newestNotifications"
        :key="notification.uid"
        class="relative rounded-3xl border border-c3-50 p-4 mt-6"
      >
        <EcIcon
          class="absolute text-c1-200 right-0 mr-3 top-0 mt-3 hover:cursor-pointer"
          icon="X"
          width="16"
          @click="handleRemoveNotification(notification.uid)"
        />
        <EcBox>
          <EcIcon icon="NotiStar" width="64" height="64" />
        </EcBox>

        <EcBox class="ml-3">
          <EcLabel class="font-semibold">{{ notification?.title }}</EcLabel>
          <EcText class="mt-3 text-c3-100"> {{ notification?.data }} </EcText>
          <EcText class="mt-3 text-c3-100">{{ notification?.time }}</EcText>
        </EcBox>
      </EcFlex>

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
          <EcText class="mt-3 text-c3-100"> </EcText>
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

export default {
  name: "DashboardNotification",
  components: { NotificationItemSkeleton },

  data() {
    return {
      isLoading: false,
      notifications: ref({}),
    }
  },
  setup() {
    const { getDashboardNotifications } = useDashboardNotification()

    return {
      getDashboardNotifications,
    }
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

    /**
     *
     * @param {*} uid
     */
    handleRemoveNotification(uid) {
      this.fetchNotifications()
    },
  },
}
</script>

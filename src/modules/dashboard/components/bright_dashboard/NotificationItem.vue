<template>
  <EcFlex class="relative rounded-3xl border p-4 mt-8" :class="[border]">
    <EcBox class="absolute text-c1-200 right-0 mr-3 top-0 mt-3 hover:cursor-pointer">
      <!-- Close icon -->
      <EcIcon v-if="!isLoading" icon="X" width="16" @click="handleReadNotification(notification.id)" />
      <EcSpinner v-else />
    </EcBox>

    <!-- Left icon -->
    <EcBox>
      <EcIcon :icon="icon" width="64" height="64" />
    </EcBox>

    <!-- Noti content -->
    <EcBox class="ml-3">
      <EcLabel class="font-semibold">{{ notification?.title }}</EcLabel>
      <EcText class="mt-3 text-base text-c3-100"> {{ notification?.content }} </EcText>
      <EcText class="mt-3 text-c3-100">{{ notification?.time }}</EcText>
    </EcBox>
  </EcFlex>
</template>

<script>
import { useNotification } from "@/modules/notification/use/useNotification"

export default {
  name: "NotificationItem",

  props: {
    notification: {
      type: Object,
      default: () => {},
    },
  },

  data() {
    return {
      isLoading: false,
    }
  },
  setup() {
    const { readNotification } = useNotification()

    return {
      readNotification,
    }
  },
  computed: {
    icon() {
      return this.notification?.pinned ? "NotiPinned" : "NotiStar"
    },
    border() {
      return this.notification?.pinned ? "border-c3-300" : "border-c3-50"
    },
  },

  methods: {
    /**
     *
     * @param {*} uid
     */
    async handleReadNotification(uid) {
      this.isLoading = true

      await this.readNotification(uid)

      this.isLoading = false

      this.$emit("callbackAfterMarkAsRead")
    },
  },
}
</script>

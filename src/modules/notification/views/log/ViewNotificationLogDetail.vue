<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="flex-wrap items-center">
      <!-- Title -->
      <EcFlex class="flex-wrap items-center justify-between w-full lg:w-auto lg:mr-4">
        <EcHeadline class="mb-3 mr-4 text-cBlack lg:mb-0">
          {{ $t("notification.logs.titleLogDetail") }}
        </EcHeadline>
      </EcFlex>
    </EcFlex>

    <!-- Content -->
    <EcBox v-if="!isLoading" class="mt-8">
      <!-- Row -->
      <EcFlex class="w-8/12 border-b border-c1-200 p-1">
        <EcLabel class="w-24 border-r border-c1-200">Name</EcLabel>
        <EcText class="ml-2">{{ notification?.title }}</EcText>
      </EcFlex>

      <!-- Row -->
      <EcFlex class="w-8/12 border-b border-c1-200 p-1">
        <EcLabel class="w-24 border-r border-c1-200">Type</EcLabel>
        <EcText class="ml-2">{{ notification?.type }}</EcText>
      </EcFlex>

      <!-- Row -->
      <EcFlex class="w-8/12 border-b border-c1-200 p-1">
        <EcLabel class="w-24 border-r border-c1-200">To</EcLabel>
        <EcBox class="ml-2">
          <EcText>{{ notification?.notifiable?.name }}</EcText>
          <EcText class="mt-1">{{ notification?.notifiable?.email }}</EcText>
        </EcBox>
      </EcFlex>

      <!-- Row -->
      <EcFlex class="w-8/12 border-b border-c1-200 p-1">
        <EcLabel class="w-24 border-r border-c1-200">Content</EcLabel>
        <EcEditor class="ml-2" :modelValue="notification?.data" :disabled="true" />
      </EcFlex>

      <!-- Row -->
      <EcFlex class="w-8/12 border-b border-c1-200 p-1">
        <EcLabel class="w-24 border-r border-c1-200">Sent</EcLabel>
        <EcText class="ml-2">{{ notification?.created_at }}</EcText>
      </EcFlex>

      <!-- Row -->
      <EcFlex class="w-8/12 border-b border-c1-200 p-1">
        <EcLabel class="w-24 border-r border-c1-200">Read At</EcLabel>
        <EcText class="ml-2">{{ notification?.read_at }}</EcText>
      </EcFlex>
    </EcBox>

    <RLoading v-else />

    <!-- Actions -->
    <EcBox class="width-full mt-8 px-4 sm:px-10">
      <EcButton variant="primary" @click="handleClickBackToNotificationLogs">
        {{ $t("notification.buttons.back") }}
      </EcButton>
    </EcBox>
  </RLayout>
</template>
<script>
import { goto } from "@/modules/core/composables"
import { useGlobalStore } from "@/stores/global"
import { reactive } from "vue"
import { useNotification } from "../../use/useNotification"

export default {
  name: "ViewNotificationLogDetail",
  props: {
    uid: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      isLoading: false,
      notification: reactive(),
    }
  },
  setup() {
    const globalStore = useGlobalStore()
    const { getNotificationLogDetail } = useNotification()
    return {
      globalStore,
      getNotificationLogDetail,
    }
  },
  computed: {},
  mounted() {
    this.fetchNotificationLogDetail()
  },

  methods: {
    /***
     * Fetch Event notifications
     */
    async fetchNotificationLogDetail() {
      this.isLoading = true
      this.notification = await this.getNotificationLogDetail(this.uid)

      this.isLoading = false
    },

    /**
     *
     */
    handleClickBackToNotificationLogs() {
      goto("ViewNotificationLog")
    },
  },
}
</script>

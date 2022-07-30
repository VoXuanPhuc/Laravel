<template>
  <EcBox :class="variantCls.root">
    <RSidebar :unreadNotification="unreadNotificationCount" />
    <RSidebarMobile :unreadNotification="unreadNotificationCount" />
    <EcBox :class="variantCls.container">
      <CBreadcrumb v-if="breadcrumbItems.length" :items="breadcrumbItems" />
      <CQuoteHeadline v-if="title" :class="variantCls.headline">
        {{ title }}
      </CQuoteHeadline>
      <slot />
    </EcBox>
  </EcBox>
</template>

<script>
import { apiNotifications } from "@/readybc/composables/api/apiNotifications"
import { fetcher } from "../api/fetcher"
import RSidebar from "./RSidebar"
import RSidebarMobile from "./RSidebarMobile"
import CBreadcrumb from "./CBreadcrumb"
import CQuoteHeadline from "./CQuoteHeadline"

export default {
  name: "RLayoutDefault",
  inject: ["getComponentVariants"],
  components: {
    RSidebar,
    RSidebarMobile,
    CBreadcrumb,
    CQuoteHeadline,
  },
  props: {
    title: {
      type: String,
      default: "",
    },
    breadcrumbItems: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      unreadNotificationCount: 0,
      intervalNotificationFetch: null,
    }
  },
  computed: {
    variants() {
      return (
        this.getComponentVariants({
          componentName: "RLayoutDefault",
          variant: "default",
        }) ?? {}
      )
    },
    variantCls() {
      return this.variants?.el || {}
    },
  },
  created() {
    this.fetchNotifications()
  },
  mounted() {
    this.intervalNotificationFetch = setInterval(() => this.fetchNotifications(), 30000)
  },
  unmounted() {
    clearInterval(this.intervalNotificationFetch)
  },
  methods: {
    async fetchNotifications(view) {
      const fragment = ""

      const variables = {
        where: {
          and: [
            {
              type_in: [
                "new_conversation",
                "request_cancel_policy",
                "request_claim",
                "client_upload_policy_document",
                "client_upload_claim_document",
              ],
            },
            { status_not: "seen" },
          ],
        },
        sort: {
          fieldName: "createdAt",
          type: "desc",
        },
        limit: 20,
      }

      const { error, data } = await apiNotifications({
        variables,
        fetcher,
        fragment,
      })
      if (error) {
        return
      }

      this.unreadNotificationCount = data.totalCount || 0
    },
  },
}
</script>

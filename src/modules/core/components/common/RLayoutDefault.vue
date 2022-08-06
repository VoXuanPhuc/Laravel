<template>
  <EcBox :class="variantCls.root">
    <RSidebar :unreadNotification="unreadNotificationCount" />
    <RSidebarMobile :unreadNotification="unreadNotificationCount" />
    <EcBox :class="variantCls.container">
      <RBreadcrumb v-if="breadcrumbItems.length" :items="breadcrumbItems" />
      <RQuoteHeadline v-if="title" :class="variantCls.headline">
        {{ title }}
      </RQuoteHeadline>
      <slot />
    </EcBox>
  </EcBox>
</template>

<script>
import { apiNotifications } from "@/readybc/composables/api/apiNotifications"
import RSidebar from "./RSidebar"
import RSidebarMobile from "./RSidebarMobile"
import RBreadcrumb from "./RBreadcrumb"
import RQuoteHeadline from "./RQuoteHeadline"
import { useGlobalStore } from "@/stores/global"

export default {
  name: "RLayoutDefault",
  inject: ["getComponentVariants"],
  components: {
    RSidebar,
    RSidebarMobile,
    RBreadcrumb,
    RQuoteHeadline,
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

  setup(props) {
    const globalStore = useGlobalStore()

    return {
      globalStore,
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
    // this.fetchNotifications()
  },
  mounted() {
    // this.intervalNotificationFetch = setInterval(() => this.fetchNotifications(), 30000)
  },
  unmounted() {
    clearInterval(this.intervalNotificationFetch)
  },
  methods: {
    async fetchNotifications(view) {
      const fragment = ""

      const variables = {}

      try {
        const { data } = await apiNotifications({
          variables,
          fragment,
        })

        this.unreadNotificationCount = data.totalCount || 0
      } catch (error) {
        console.log(error)
        // this.globalStore.addToastMessage({
        //   type: "error",
        //   content: {
        //     type: "message",
        //     text: error?.message,
        //   },
        // })
      }
    },
  },
}
</script>

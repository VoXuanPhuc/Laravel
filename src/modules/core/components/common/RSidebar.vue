<template>
  <EcBox :class="variantCls.root">
    <EcFlex :class="variantCls.image.wrapper">
      <img :src="computedLogo" alt="Logo" :class="variantCls.image.img" />
      <!-- class="flex-0 w-16 cursor-pointer" style="min-height: 78px" -->
    </EcFlex>
    <RSidebarMenu :unreadNotification="unreadNotification" class="flex-grow" />
  </EcBox>
</template>

<script>
import RSidebarMenu from "./RSidebarMenu"
import { useGlobalStore } from "@/stores/global"

export default {
  name: "RSidebar",
  inject: ["getComponentVariants"],
  components: {
    RSidebarMenu,
  },
  props: {
    unreadNotification: {
      type: Number,
      default: 0,
    },
  },
  /* eslint-disable */
  setup() {
    const globalStore = useGlobalStore()
    const logoImg = ""

    return {
      logoImg,
      globalStore,
    }
  },

  beforeMount() {
    this.decideImageToRender()
  },
  computed: {
    variants() {
      return (
        this.getComponentVariants({
          componentName: "RSidebar",
          variant: "default",
        }) ?? {}
      )
    },
    variantCls() {
      return this.variants?.el || {}
    },

    computedLogo() {
      return this.logoImg || "https://via.placeholder.com/64x78"
    },
  },

  methods: {
    /**
     * Render image
     */
    async decideImageToRender() {
      const { tenantId } = this.globalStore?.getTenantClientId
      const tenantConfig = this.globalStore?.getTenantSettings

      try {
        const fileName = tenantConfig?.default?.logo?.sidebar

        this.logoImg = tenantConfig?.server?.logo || require(`@/tenants/${tenantId}/assets/${fileName}`)
        // eslint-disable-next-line no-empty
      } catch (error) {
        console.groupCollapsed(
          "%c Tenant config file does not exist",
          "padding: 1px 6px 1px 0px; background: yellow; color: black"
        )
        console.log(`tenantId: ${tenantId}`)
        console.log(error)
        console.groupEnd()
      }
    },
  },
  /* eslint-enable */
}
</script>

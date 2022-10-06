<template>
  <EcFlex :class="variantCls.root">
    <!-- Image -->
    <EcFlex :class="variantCls.image.wrapper">
      <img :class="variantCls.image.img" :src="computedLogo" alt="Login Image" />
    </EcFlex>

    <!-- SVGs & Auth slot -->
    <EcFlex :class="variantCls.svgs.wrapper">
      <EcBox :class="variantCls.svgs.mobile.class" :style="variantCls.svgs.mobile.style">
        <svg width="100vw" height="30vw" viewBox="0 0 100 30" xmlns="http://www.w3.org/2000/svg">
          <path d="M0 22 C30 25, 50 20, 100 0 L100 30 0 30Z" fill="currentColor" />
        </svg>
      </EcBox>
      <EcBox :class="variantCls.svgs.desktop.class" :style="variantCls.svgs.desktop.style">
        <svg width="30vh" height="100vh" viewBox="0 0 30 100" xmlns="http://www.w3.org/2000/svg">
          <path d="M17 0 C-20 40, 35 65, 28 100 L0 100 0 0Z" fill="currentColor" />
        </svg>
      </EcBox>
      <slot />
    </EcFlex>

    <!-- Background -->
    <EcBox :class="variantCls.background.class" :style="variantCls.background.style" />
  </EcFlex>
</template>

<script>
import { useGlobalStore } from "@/stores/global"

export default {
  name: "LayoutAuth",
  inject: ["getComponentVariants"],

  setup() {
    const globalStore = useGlobalStore()

    const logoImg = ""

    return {
      logoImg,
      globalStore,
    }
  },

  async beforeMount() {
    await this.decideImageToRender()
  },
  computed: {
    variants() {
      return (
        this.getComponentVariants({
          componentName: "LayoutAuth",
          variant: "default",
        }) ?? {}
      )
    },
    variantCls() {
      var data = this.variants?.el || {}
      return data
    },

    computedLogo() {
      return this.logoImg || "https://via.placeholder.com/400x300"
    },
  },

  methods: {
    async decideImageToRender() {
      const { tenantId } = this.globalStore.getTenantClientId
      const tenantConfig = this.globalStore.getTenantSettings

      try {
        const fileName = tenantConfig?.default?.logo?.login

        this.logoImg = tenantConfig?.server?.logo || (await import(`@/tenants/${tenantId}/assets/${fileName}`))

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
}
</script>

<template>
  <EcFlex :class="variantCls.root">
    <!-- Image -->
    <EcFlex :class="variantCls.left.wrapper">
      <EcBox :class="variantCls.left.desktop.bg">
        <EcFlex :class="variantCls.left.container">
          <img :class="variantCls.left.desktop.img" src="@/assets/images/login_logo.svg" />
          <EcText v-if="!isLandlord" class="text-cWhite mt-8 text-2xl font-bold">{{ $t("auth.clientPortal") }}</EcText>
        </EcFlex>
      </EcBox>

      <EcBox :class="variantCls.left.mobile.bg">
        <EcFlex :class="variantCls.left.mobile.container">
          <img :class="variantCls.left.mobile.img" src="@/assets/images/login_logo.svg" />
          <EcText v-if="!isLandlord" class="text-cWhite mt-8 text-2xl font-bold">{{ $t("auth.clientPortal") }}</EcText>
        </EcFlex>
      </EcBox>
    </EcFlex>

    <!-- SVGs & Auth slot -->
    <EcFlex :class="variantCls.svgs.wrapper">
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

  data() {
    return {}
  },
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

    isLandlord() {
      return this.globalStore.isLandlord
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

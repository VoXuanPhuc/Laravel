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
import { useStore } from "vuex"
import { computed, shallowRef, onBeforeMount } from "vue"
export default {
  name: "LayoutAuth",
  inject: ["getComponentVariants"],

  setup() {
    const store = useStore()

    const logoImg = shallowRef(null)
    const computedLogo = computed(() => logoImg.value || "https://via.placeholder.com/400x300")

    async function decideImageToRender() {
      const { tenantId, clientId } = store?.state
      try {
        const tenantConfig = await import(`@/tenants/${tenantId}/configs/${clientId}.js`)
        const fileName = tenantConfig?.default?.logo?.login

        const image = await import(`@/tenants/${tenantId}/assets/${fileName}`)
        logoImg.value = image?.default
        // eslint-disable-next-line no-empty
      } catch (error) {
        console.groupCollapsed(
          "%c Tenant config file does not exist",
          "padding: 1px 6px 1px 0px; background: yellow; color: black"
        )
        console.log(`tenantId: ${tenantId}`)
        console.log(`clientId: ${clientId}`)
        console.log(error)
        console.groupEnd()
      }
    }

    onBeforeMount(async () => {
      await decideImageToRender()
    })

    return {
      computedLogo,
    }
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
  },
}
</script>

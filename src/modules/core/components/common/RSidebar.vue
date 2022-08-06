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
import { onBeforeMount, shallowRef, computed } from "vue"
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
    const logoImg = shallowRef(null)

    const decideImageToRender = async () => {
      const { tenantId, clientId } = globalStore?.getTenantClientId
      try {
        const tenantConfig = await import(`@/tenants/${tenantId}/configs/${clientId}.js`)
        const fileName = tenantConfig?.default?.logo?.sidebar

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
    const computedLogo = computed(() => logoImg.value || "https://via.placeholder.com/64x78")
    return {
      computedLogo,
    }
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
  },
  /* eslint-enable */
}
</script>

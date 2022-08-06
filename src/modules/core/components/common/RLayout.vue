<template>
  <component :is="importedLayout || 'RLayoutDefault'">
    <slot></slot>
  </component>
</template>

<script>
import { useGlobalStore } from "@/stores/global"
import { shallowRef, onBeforeMount } from "vue"
import RLayoutDefault from "./RLayoutDefault"

export default {
  name: "RLayout",
  components: {
    RLayoutDefault,
  },
  setup() {
    const globalStore = useGlobalStore()
    const importedLayout = shallowRef(null)

    async function decideComponent() {
      const tenantId = globalStore.getTenantId
      const clientId = globalStore.getClientId
      const layoutFileName = globalStore.getTenantSettings?.layout_filename
      try {
        const component = await import(`@/tenants/${tenantId}/layout/${layoutFileName}`)
        importedLayout.value = component?.default
      } catch (error) {
        console.groupCollapsed(
          "%c Layout config file does not exist",
          "padding: 1px 6px 1px 0px; background: yellow; color: black"
        )
        console.log(`tenantId: ${tenantId}`)
        console.log(`clientId: ${clientId}`)
        console.log(error)
        console.groupEnd()
      }
    }
    onBeforeMount(() => {
      decideComponent()
    })
    return { importedLayout }
  },
}
</script>

<template>
  <component :is="importedLayout || 'RLayoutDefault'">
    <slot></slot>
  </component>
</template>

<script>
import { shallowRef, onBeforeMount } from "vue"
import { useStore } from "vuex"
import RLayoutDefault from "./RLayoutDefault"
export default {
  name: "CLayout",
  components: {
    RLayoutDefault,
  },
  setup() {
    const store = useStore()
    const importedLayout = shallowRef(null)
    async function decideComponent() {
      const tenantId = store?.state?.tenantId
      const clientId = store?.state?.clientId
      const layoutFileName = store?.state?.tenantSettings?.layout_filename
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

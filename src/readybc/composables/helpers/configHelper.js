import { useGlobalStore } from "@/stores/global"
import { fetchConfigs } from "@/readybc/composables/api/apiConfigs"

/**
 *
 * Build configs
 * @returns
 */
export const buildConfigs = async () => {
  const globalStore = useGlobalStore()

  try {
    // Default file configs
    const config = await import(`@/tenants/default/configs/default.js`)

    // Configs from server
    const { data } = await fetchConfigs()

    if (!data?.isActive) {
      globalStore.addErrorToastMessage("Tenant is suspended")

      window.location.href = process.env.VUE_APP_BASE_URL + "/tenant"
    }
    config.server = data

    return config

    // eslint-disable-next-line no-empty
  } catch (error) {
    globalStore.addErrorToastMessage("Unable to get default configs")

    console.groupCollapsed("%c Default config file does not exist", "padding: 1px 6px 1px 0px; background: yellow; color: black")
    console.log(error)
    console.groupEnd()
    return {}
  }
}

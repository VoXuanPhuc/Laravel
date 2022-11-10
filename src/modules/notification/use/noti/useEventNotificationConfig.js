import { useGlobalStore } from "@/stores/global"
import * as api from "@/modules/notification/api/eventNofiticationFetcher"
import { ref } from "vue"

export function useEventNotificationConfig() {
  const globalStore = useGlobalStore()
  const configs = ref({})
  /**
   *
   * @param {*} filters
   * @returns
   */
  const getNotificationConfigs = async () => {
    try {
      const { data } = await api.fetchEventNotificationConfigs()

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : this.$t("notification.errors.listTemplates"))
    }
  }

  /**
   *
   * @param {*} str
   * @returns
   */
  const ucFirst = (str) => {
    return str.charAt(0).toUpperCase() + str.slice(1)
  }

  /***
   *
   */
  const buildReplacement = (key, replacement) => {
    return `{{${key}.${replacement}}}`
  }

  /**
   *
   * @param {*} value
   */
  const copyValue = (value) => {
    const el = document.createElement("textarea")
    el.value = value
    el.setAttribute("readonly", "")
    el.style.position = "absolute"
    el.style.left = "-9999px"
    document.body.appendChild(el)
    const selected = document.getSelection().rangeCount > 0 ? document.getSelection().getRangeAt(0) : false
    el.select()
    document.execCommand("copy")
    document.body.removeChild(el)
    if (selected) {
      document.getSelection().removeAllRanges()
      document.getSelection().addRange(selected)
    }

    globalStore.addSuccessToastMessage("Copied")
  }

  return {
    configs,
    ucFirst,
    copyValue,
    buildReplacement,
    getNotificationConfigs,
  }
}

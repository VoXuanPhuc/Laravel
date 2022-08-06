import { useGlobalStore } from "@/stores/global"
import { i18n } from "@/setups/i18nConfig"

function logout() {
  const globalStore = useGlobalStore()
  globalStore.logout()
}

function centralizeError(error) {
  // axios error format
  const { data, status } = error?.response || {}
  // BE error format
  const { code, message } = data?.error || {}

  return {
    error,
    code: code || status,
    message: message || error?.message,
  }
}

export function defaultErrorHandler(error = {}) {
  const globalStore = useGlobalStore()
  const { code, message } = centralizeError(error)

  if (code === 500) {
    globalStore.addToastMessage({
      type: "error",
      content: {
        type: "message",
        text: i18n.global.t("errors.network"),
      },
    })
    return
  }

  if (code === 401) {
    globalStore.addToastMessage({
      type: "error",
      content: {
        type: "message",
        text: i18n.global.t("errors.token"),
      },
    })

    setTimeout(() => {
      logout()
    }, 1500)
    return
  }

  if (code === 403) {
    globalStore.addToastMessage({
      type: "error",
      content: {
        type: "message",
        text: i18n.global.t("errors.permission"),
      },
    })
    return
  }

  if (code === 404) {
    globalStore.addToastMessage({
      type: "error",
      content: {
        type: "message",
        text: i18n.global.t("errors.notFound"),
      },
    })
    return
  }

  return { error, code, message }
}

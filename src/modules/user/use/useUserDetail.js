import * as api from "../api/fetcher"
import { useGlobalStore } from "@/stores/global"
import { handleErrorForUser } from "../api"
import { useI18n } from "vue-i18n"

export function useUserDetail() {
  const globalStore = useGlobalStore()

  const { t } = useI18n()

  // Get user detail
  async function getUserDetail(userId) {
    try {
      const { data } = await api.getUserDetail(userId)

      if (!data || data.error) {
        handleErrorForUser({ error: data?.error, $t: t })
      }

      return data
    } catch (error) {
      globalStore.addToastMessage({ type: "error", content: error?.message })
      return error
    }
  }

  async function updateUser(userId, payload) {
    try {
      const { data } = await api.updateUser(userId, payload)

      if (!data || data.error) {
        handleErrorForUser({ error: data?.error, $t: t })
      } else {
        globalStore.addToastMessage({
          type: "success",
          content: {
            type: "message",
            text: `Updated`,
          },
        })
      }
    } catch (error) {
      globalStore.addToastMessage({ type: "error", content: error?.message })
      return error
    }
  }

  return {
    getUserDetail,
    updateUser,
  }
}

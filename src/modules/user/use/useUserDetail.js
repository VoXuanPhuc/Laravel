import * as api from "../api/userFetcher"
import { useGlobalStore } from "@/stores/global"
import { handleErrorForUser } from "../api"
import { useI18n } from "vue-i18n"
import { goto } from "@/modules/core/composables"

export function useUserDetail() {
  const globalStore = useGlobalStore()

  const { t } = useI18n()

  /**
   * Get user detail
   * @param {*} userId
   * @returns
   */
  async function getUserDetail(userId) {
    try {
      const { data } = await api.getUserDetail(userId)

      if (!data || data.error) {
        handleErrorForUser({ error: data?.error, $t: t })
      }

      return data
    } catch (error) {
      globalStore.addErrorToastMessage({ type: "error", content: error?.message })
      return {}
    }
  }

  /**
   *
   * @param {*} userId
   * @param {*} payload
   * @returns
   */
  async function updateUser(userId, payload) {
    try {
      const { data } = await api.updateUser(userId, payload)

      if (!data || data.error) {
        handleErrorForUser({ error: data?.error, $t: t })
      } else {
        globalStore.addSuccessToastMessage("Updated")
      }
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
      return error
    }
  }

  /**
   * Delete user with id
   * @param {*} userId
   */
  async function deleteUser(userId) {
    try {
      await api.deleteUser(userId)

      globalStore.addSuccessToastMessage("Deleted!")
      goto("ViewUserList")
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  /**
   *
   * @param {*} userId
   * @param {*} roleUid
   */
  async function assignRole(userId, roleUid) {
    try {
      const payload = {
        role_uid: roleUid,
      }

      const { data } = await api.assignRole(userId, payload)

      globalStore.addSuccessToastMessage("Role has been update!")
      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  return {
    getUserDetail,
    updateUser,
    deleteUser,
    assignRole,
  }
}

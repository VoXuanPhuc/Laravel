import * as api from "../api/activityFetcher"
import { useGlobalStore } from "@/stores/global"
import { ref } from "vue"

export function useActivityDetail() {
  const globalStore = useGlobalStore()

  const statuses = ref([
    { value: 1, name: "Created" },
    { value: 2, name: "In Progress" },
    { value: 3, name: "Finished" },
    { value: 4, name: "Canceled" },
  ])

  /**
   *
   * @param {*} payload
   * @returns
   */
  const getActivity = async (uid, relations = []) => {
    try {
      const { data } = await api.fetchActivity(uid, relations)

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  /**
   *
   * @param {*} payload
   * @returns
   */
  const updateActivity = async (payload, uid) => {
    try {
      const { data } = await api.updateActivity(payload, uid)
      globalStore.addSuccessToastMessage("Updated activity, redirect to next step...")

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  /**
   *
   * @param {*} uid
   * @returns
   */
  const cancelActivity = async (uid) => {
    try {
      const { data } = await api.permanentDelete(uid)

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }
  return {
    getActivity,
    updateActivity,
    cancelActivity,
    statuses,
  }
}

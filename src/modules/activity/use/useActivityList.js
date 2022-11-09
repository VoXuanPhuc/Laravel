import { ref } from "vue"
import * as api from "../api/activityFetcher"
import { useI18n } from "vue-i18n"
import { useGlobalStore } from "@/stores/global"

export function useActivityList() {
  const globalStore = useGlobalStore()

  // Initial data
  const totalItems = ref(0)
  const skip = ref(0)
  const limit = ref(10)
  const currentPage = ref(1)

  const activities = ref([])

  const { t } = useI18n()

  /**
   *
   * @returns
   */
  async function getActivityList() {
    try {
      const { data } = await api.fetchActivities()

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : this.$t("activity.errors.listActivity"))
    }
  }

  /**
   *
   * @param {*} divisionUid
   * @returns
   */
  async function fetchActivityListByDivisionUid(divisionUid) {
    try {
      const { data } = await api.fetchActivityListByDivisionUid(this.$t("activity.firstOrganizationId"), divisionUid)

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : this.$t("activity.errors.listActivity"))
    }
  }

  /**
   * Download activities
   * @returns
   */
  async function downloadActivities(divisionUid, businessUnitUid) {
    try {
      const { data } = await api.downloadActivities(divisionUid, businessUnitUid)

      if (!data) {
        globalStore.addErrorToastMessage(this.$t("activity.errors.download"))
        return
      }

      const url = window.URL.createObjectURL(new Blob([data]))
      const link = document.createElement("a")
      link.href = url

      link.setAttribute("download", `Activities_${Date.now()}.xlsx`)
      document.body.appendChild(link)
      link.click()
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : this.$t("activity.errors.download"))
      return false
    }
  }

  /**
   *
   * @param {*} uid
   * @returns
   */
  const deletePermanentActivity = async (uid) => {
    try {
      const { data } = await api.permanentDelete(uid)

      globalStore.addSuccessToastMessage(t("activity.messages.deleteSuccess"))

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  return {
    getActivityList,
    downloadActivities,
    fetchActivityListByDivisionUid,
    deletePermanentActivity,
    activities,
    t,
    totalItems,
    skip,
    limit,
    currentPage,
  }
}

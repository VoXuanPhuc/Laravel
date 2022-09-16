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

  // Fetch activities lists
  async function getActivityList() {
    try {
      const { data } = await api.fetchActivities()

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : this.$t("activity.errors.listActivity"))
    }
  }

  async function fetchActivityListByDivisionUid(divisionUid) {
    try {
      const { data } = await api.fetchActivityListByDivisionUid(this.$t("activity.firstOrganizationId"), divisionUid)

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : this.$t("activity.errors.listActivity"))
    }
  }

  return {
    getActivityList,
    fetchActivityListByDivisionUid,
    activities,
    t,
    totalItems,
    skip,
    limit,
    currentPage,
  }
}

import * as api from "@/readybc/composables/api/apiApplications"

export function useApplications() {
  /**
   *
   * @returns
   */
  const getApplications = async () => {
    try {
      const { data } = await api.fetchApplications()
      return data
    } catch (error) {
      return error
    }
  }

  return {
    getApplications,
  }
}

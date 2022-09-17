import * as api from "@/readybc/composables/api/apiEquipments"

export function useEquipments() {
  /**
   *
   * @returns
   */
  const getEquipments = async () => {
    try {
      const { data } = await api.fetchEquipments()
      return data
    } catch (error) {
      return error
    }
  }

  return {
    getEquipments,
  }
}

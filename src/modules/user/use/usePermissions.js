import * as api from "@/readybc/composables/api/apiPermissions"

export function usePermissions() {
  /**
   * Get permissions
   */
  const getPermissions = async () => {
    try {
      const { data } = await api.fetchPermissions()

      return data
    } catch (error) {
      return error
    }
  }

  /**
   * Get permissions with group
   */
  const getPermissionsWithGroup = async () => {
    try {
      const { data } = await api.fetchPermissionsWithGroup()

      return data
    } catch (error) {
      return error
    }
  }

  return {
    getPermissions,
    getPermissionsWithGroup,
  }
}

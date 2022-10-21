import * as api from "../../api/resourceFetcher"
import { useGlobalStore } from "@/stores/global"
import { required } from "@vuelidate/validators"
import { ref } from "vue"
import useVuelidate from "@vuelidate/core"

export function useResourceDetail() {
  const globalStore = useGlobalStore()

  const resource = ref({
    category: {},
    owners: [{ uid: "" }],
  })

  const rules = {
    resource: {
      name: { required },
      description: {},
      category: {},
      owners: {},
    },
  }

  const v$ = useVuelidate(rules, { resource })

  /**
   *
   * @param {*} payload
   * @returns
   */
  const getResource = async (uid) => {
    try {
      const { data } = await api.fetchResource(uid)

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
  const updateResource = async (payload, uid) => {
    try {
      const { data } = await api.updateResource(payload, uid)
      globalStore.addSuccessToastMessage("Updated resource")

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
  const deleteResource = async (uid) => {
    try {
      const response = await api.deleteResource(uid)

      return response.status === 200
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }
  return {
    getResource,
    updateResource,
    deleteResource,
    resource,
    v$,
  }
}

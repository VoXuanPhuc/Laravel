import { useGlobalStore } from "@/stores/global"
import * as api from "../../api/ownerFetcher"
import { ref } from "vue"
import { required, email } from "@vuelidate/validators"
import useVuelidate from "@vuelidate/core"

export function useOwnerDetail() {
  const globalStore = useGlobalStore()

  const form = ref({
    is_invite: true,
  })

  const rules = {
    form: {
      first_name: { required },
      last_name: { required },
      email: { required, email },
    },
  }

  const v$ = useVuelidate(rules, { form })

  /**
   *
   * @param {*} uid
   * @returns
   */
  async function getResourceOwner(uid) {
    try {
      const { data } = await api.fetchOwner(uid)

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error.message : this.$t("resource.owner.errors.fetchOwner"))
    }
  }

  /**
   *
   * @param {*} uid
   * @returns
   */
  async function updateResourceOwner(payload, uid) {
    try {
      const { data } = await api.updateOwner(payload, uid)

      globalStore.addSuccessToastMessage(this.$t("resource.owner.messages.updateOwner"))
      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error.message : this.$t("resource.owner.errors.updateOwner"))
    }
  }

  /**
   *
   * @param {*} uid
   * @returns
   */
  async function deleteResourceOwner(uid) {
    try {
      const response = await api.deleteOwner(uid)

      globalStore.addSuccessToastMessage(this.$t("resource.owner.messages.deleteOwner"))
      return response.status === 200
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error.message : this.$t("resource.owner.errors.deleteOwner"))
    }
  }

  return {
    getResourceOwner,
    updateResourceOwner,
    deleteResourceOwner,
    form,
    v$,
  }
}

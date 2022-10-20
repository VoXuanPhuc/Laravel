import { useGlobalStore } from "@/stores/global"
import * as api from "../../api/ownerFetcher"
import { ref } from "vue"
import { required, email } from "@vuelidate/validators"
import useVuelidate from "@vuelidate/core"

export function useOwnerNew() {
  const globalStore = useGlobalStore()
  const form = ref({
    is_invite: false,
  })

  const rules = {
    form: {
      first_name: { required },
      last_name: { required },
      email: { required, email },
      is_invite: { required },
    },
  }

  const v$ = useVuelidate(rules, { form })

  async function createOwner(payload) {
    try {
      const { data } = await api.createOwner(payload)

      globalStore.addSuccessToastMessage(this.$t("resource.owner.messages.createdOwner"))

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error.message : this.$t("resource.owner.errors.createOwner"))
    }
  }

  return {
    createOwner,
    form,
    v$,
  }
}

import * as api from "../api/fetcher"
import { useI18n } from "vue-i18n"
import { useGlobalStore } from "@/stores/global"
import { ref } from "vue"
import { required } from "@vuelidate/validators"
import useVuelidate from "@vuelidate/core"

export const useForceChangePassword = () => {
  const { t } = useI18n()
  const globalStore = useGlobalStore()

  // Form and validation
  const form = ref({})

  const rules = {
    form: {
      // current_password: { required },
      new_password: { required },
      confirm_password: {
        required,
        // sameAsPassword: helpers.withMessage("Confirm password must match new password", sameAs(form.value.new_password)),
      },
    },
  }

  const v$ = useVuelidate(rules, { form })

  /**
   *
   * @param {*} payload
   * @returns
   */
  const submitChangePassword = async (payload) => {
    try {
      const { data } = await api.forceChangeNewPassword(payload)

      if (!data || !data.accessToken) {
        throw new Error(t("auth.errors.changePassword"))
      }

      globalStore.addSuccessToastMessage(t("auth.updatePasswordSuccessNote"))
      return true
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : t("auth.errors.changePassword"))
      return false
    }
  }

  return {
    submitChangePassword,
    form,
    v$,
  }
}

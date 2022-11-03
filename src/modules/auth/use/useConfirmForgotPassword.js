import * as api from "../api/fetcher"
import { useI18n } from "vue-i18n"
import { useGlobalStore } from "@/stores/global"
import { ref } from "vue"
import { required } from "@vuelidate/validators"
import useVuelidate from "@vuelidate/core"

export const useConfirmForgotPassword = () => {
  const { t } = useI18n()
  const globalStore = useGlobalStore()
  const FORGOT_PASSWORD_RES = {
    SUCCESS: "passwords.reset",
    INVALID_TOKEN: "passwords.token",
  }
  // Form and validation
  const form = ref({})

  const rules = {
    form: {
      confirmation_code: { required },
      new_password: { required },
      confirm_password: {
        required,
        // sameAsPassword: helpers.withMessage("Confirm password must match new password", sameAs(form.value.new_password)),
      },
      email: { required },
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
      const { data } = await api.confirmForgotPassword(payload)

      if (data === FORGOT_PASSWORD_RES.INVALID_TOKEN) {
        globalStore.addErrorToastMessage(t("auth.errors.codeError"))
        return false
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

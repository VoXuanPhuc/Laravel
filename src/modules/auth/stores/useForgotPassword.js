import useVuelidate from "@vuelidate/core"
import { email, required } from "@vuelidate/validators"
import { defineStore } from "pinia"
import { ref, computed } from "vue"
import { useRouter } from "vue-router"
import * as api from "../api/fetcher"
import { useGlobalStore } from "@/stores/global"
import { useI18n } from "vue-i18n"

export const useForgotPassword = defineStore("forgotPassword", () => {
  const { t } = useI18n()

  const formEmail = ref("")
  const router = useRouter()
  const FORGOT_PASSWORD_RES = {
    SUCCESS: "passwords.sent",
    THROTTLED: "passwords.throttled",
  }

  const rules = computed(() => ({
    formEmail: {
      required,
      email,
    },
  }))

  const v$ = useVuelidate(rules, { formEmail })

  // --- ACTIONS --
  // To Login Page
  function toLoginPage() {
    router.push({
      name: "ViewLogin",
    })
  }

  /**
   *
   * @returns {Promise<boolean>}
   */
  async function sendForgotEmail(payload) {
    const globalStore = useGlobalStore()

    try {
      const { data } = await api.forgotPassword({ email: payload })
      if (data === FORGOT_PASSWORD_RES.THROTTLED) {
        globalStore.addSuccessToastMessage(t("auth.alreadySendCode"))
        return true
      }
      globalStore.addSuccessToastMessage(t("auth.sendCodeSuccess"))
      return data === FORGOT_PASSWORD_RES.SUCCESS
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : "auth.errors.sendCode")
    }
  }

  // Return
  return {
    formEmail,
    toLoginPage,
    sendForgotEmail,
    v$,
  }
})

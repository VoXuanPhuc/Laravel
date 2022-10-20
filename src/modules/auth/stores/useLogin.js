import useVuelidate from "@vuelidate/core"
import { email, required } from "@vuelidate/validators"
import dayjs from "dayjs"
import { defineStore } from "pinia"
import { computed, ref } from "vue"
import * as api from "../api/fetcher"
import { useGlobalStore } from "@/stores/global"
import { useI18n } from "vue-i18n"

export const useLoginStore = defineStore("login", () => {
  const { t } = useI18n()

  const CHALLENGE_CHANGE_PASSWORD = "NEW_PASSWORD_REQUIRED"

  const form = ref({
    username: "",
    password: "",
  })

  const rules = computed(() => ({
    form: {
      username: {
        required,
        email,
      },
      password: {
        required,
      },
    },
  }))

  // Validations
  const v = useVuelidate(rules, { form })

  // == ACTIONS ==
  async function login() {
    const globalStore = useGlobalStore()

    try {
      const { data } = await api.login({
        username: this.form.username,
        password: this.form.password,
      })

      if (data && data.challengeName) {
        return data
      }

      if (!data || !data.accessToken) {
        throw new Error(t("auth.errors.login"))
      }

      localStorage.setItem(process.env.VUE_APP_TOKEN_KEY, data.accessToken)
      localStorage.setItem(process.env.VUE_APP_ID_TOKEN_KEY, data.idToken)
      localStorage.setItem(process.env.VUE_APP_TOKEN_EXPIRES, dayjs().add(data.expiresIn, "second"))
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : t("auth.errors.login"))
    }
  }

  return {
    form,
    v,
    login,
    CHALLENGE_CHANGE_PASSWORD,
  }
})

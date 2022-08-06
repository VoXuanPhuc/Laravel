import useVuelidate from "@vuelidate/core"
import { email, required } from "@vuelidate/validators"
import dayjs from "dayjs"
import { defineStore } from "pinia"
import { computed, ref } from "vue"
import * as api from "../api/fetcher"
import { useGlobalStore } from "@/stores/global"

export const useLoginStore = defineStore("login", () => {
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

      if (!data || !data.accessToken) {
        throw new Error(this.$t("auth.login"))
      }

      localStorage.setItem(process.env.VUE_APP_TOKEN_KEY, data.accessToken)
      localStorage.setItem(process.env.VUE_APP_TOKEN_EXPIRES, dayjs().add(data.expiresIn, "second"))
    } catch (error) {
      globalStore.addToastMessage({ type: "error", content: error?.message })
    }
  }

  return {
    form,
    v,
    login,
  }
})

import useVuelidate from "@vuelidate/core"
import { email, required } from "@vuelidate/validators"
import { defineStore } from "pinia"
import { ref, computed } from "vue"
import { useRouter } from "vue-router"

export const useForgotPassword = defineStore("forgotPassword", () => {
  const formEmail = ref("")
  const router = useRouter()

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

  function sendForgotEmail() {
    console.log(this.formEmail)
  }

  // Return
  return {
    formEmail,
    toLoginPage,
    sendForgotEmail,
    v$,
  }
})

import { ref } from "vue"
import { required, email } from "@vuelidate/validators"
import { useVuelidate } from "@vuelidate/core"
import { Machine } from "xstate"
import { forgotPasswordMachine } from "../machine/forgotPasswordMachine"
import { apiPromise } from "@/readybc/composables/api/apiPromise"
import { apiForgotPassword } from "@/readybc/composables/login/apiForgotPassword"
import { fetcher } from "./../api/fetcher"
import { useStore } from "vuex"
import { useRouter } from "vue-router"
import { useMachine } from "@/modules/core/composables/useMachine"

export function useForgotPassword() {
  // Initial data
  const formEmail = ref("")

  const rules = {
    formEmail: { required, email },
  }
  const v = useVuelidate(rules, { formEmail })

  const store = useStore()
  const router = useRouter()

  const options = {
    services: {
      async sendMail() {
        const variables = {
          tenantId: store.state.tenantId,
          input: {
            clientId: store.state.clientId,
            email: formEmail.value,
          },
        }
        return await apiPromise(apiForgotPassword, {
          variables,
          fetcher,
        })
      },
    },
    actions: {
      backToLogin() {
        router.push({
          name: "ViewLogin",
        })
      },
    },
  }

  const { state, send } = useMachine(Machine(forgotPasswordMachine, options), {
    devTools: process.env.NODE_ENV === "development",
  })

  return {
    state,
    send,
    v,
    formEmail,
  }
}

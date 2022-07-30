import { computed, onMounted, ref } from "vue"
import { dashboardMachine } from "../machine/dashboardMachine"
import { fetcher } from "./../api/fetcher"
import { handleErrorForUser } from "./../api/handleErrorForUser"
import { Machine } from "xstate"
import { useMachine } from "@/modules/core/composables/useMachine"
import { apiPromise } from "@/readybc/composables/api/apiPromise"
import { apiIndividuals } from "@/readybc/composables/api/apiIndividuals"
import { apiPolicies } from "@/readybc/composables/api/apiPolicies"
import { apiCases } from "@/readybc/composables/api/apiCases"
import { useI18n } from "vue-i18n"

export function useDashboard() {
  // Initial data
  const clientNumber = ref(0)
  const policyNumber = ref(0)
  const caseNumber = ref(0)

  // Computed data
  const statisticList = computed(() => [
    {
      name: "clients",
      label: t("dashboard.clients"),
      icon: "UserGroup",
      backgroundColor: "bg-c1-100",
      iconColor: "text-c1-500",
      count: clientNumber.value,
      navigation: {
        name: "ViewClientCreate",
      },
    },
    {
      name: "policies",
      label: t("dashboard.policies"),
      icon: "ClipboardCheck",
      backgroundColor: "bg-cWarning-100",
      iconColor: "text-cWarning-500",
      count: policyNumber.value,
      navigation: null,
    },
    {
      name: "cases",
      label: t("dashboard.cases"),
      icon: "UserGroup",
      backgroundColor: "bg-cError-100",
      iconColor: "text-c2-400",
      count: caseNumber.value,
      navigation: {
        name: "ViewCaseNew",
      },
    },
  ])

  const { t } = useI18n()

  const options = {
    services: {
      async readClient() {
        const variables = {}
        const fragment = ""
        return await apiPromise(apiIndividuals, {
          variables,
          fetcher,
          fragment,
        })
      },
      async readPolicy() {
        const variables = {}
        const fragment = ""

        return await apiPromise(apiPolicies, {
          variables,
          fetcher,
          fragment,
        })
      },
      async readCase() {
        const variables = {}
        const fragment = ""
        return await apiPromise(apiCases, {
          variables,
          fetcher,
          fragment,
        })
      },
    },
    actions: {
      setClientNumber(ctx, event) {
        clientNumber.value = event?.data?.totalCount
      },
      setPolicyNumber(ctx, event) {
        policyNumber.value = event?.data?.totalCount
      },
      setCaseNumber(ctx, event) {
        caseNumber.value = event?.data?.totalCount
      },
      setErrorMessage(ctx, event) {
        handleErrorForUser({ error: event?.data, $t: t })
      },
    },
  }

  onMounted(() => {
    send("FETCH")
  })

  const { state, send } = useMachine(Machine(dashboardMachine, options), { devTools: process.env.NODE_ENV === "development" })

  return {
    state,
    send,
    statisticList,
  }
}

import { computed, ref } from "vue"

import { useRoute, useRouter } from "vue-router"
// import { handleErrorForUser } from "../api/handleErrorForUser"
// import { useI18n } from "vue-i18n"

export function useOrganizationDetail() {
  // Initial data
  const organizationName = ref("")
  const organizationInfo = ref({})

  // Delete participant data

  const route = useRoute()
  const router = useRouter()
  // const { t } = useI18n()
  console.log(router)
  // Computed data
  const organizationId = computed(() => route.params.organizationId)

  return {
    organizationId,
    organizationName,
    organizationInfo,
  }
}

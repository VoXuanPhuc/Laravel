import { onMounted, ref } from "vue"
import { useI18n } from "vue-i18n"
// import { fetcher } from "./../api/fetcher"
// import { handleErrorForUser } from "../api/handleErrorForUser"

export function useOrganizationList() {
  // Initial data
  const searchQuery = ref("")
  const organizationHeader = ref([])
  const organizationList = ref([])
  const totalItems = ref(0)
  const skip = ref(0)
  const limit = ref(10)
  const currentPage = ref(1)
  const { t } = useI18n()

  const intialHeader = () => {
    organizationHeader.value = [
      {
        name: "name",
        label: t("organization.name"),
      },
      {
        name: "code",
        label: t("organization.code"),
      },
      {
        name: "active",
        label: t("organization.active"),
      },
      {
        name: "created-at",
        label: t("organization.createdAt"),
      },
    ]
  }

  onMounted(() => {
    intialHeader()
  })

  return {
    searchQuery,
    organizationHeader,
    organizationList,
    totalItems,
    skip,
    limit,
    currentPage,
    t,
  }
}

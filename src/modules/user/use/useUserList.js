import { reactive, ref, toRefs } from "vue"
import * as api from "../api/userFetcher"
import { useI18n } from "vue-i18n"
import { useGlobalStore } from "@/stores/global"
import { handleErrorForUser } from "../api"
// import dayjs from "dayjs"

export function useUserList() {
  const globalStore = useGlobalStore()

  // Initial data
  const searchQuery = ref("")
  const totalItems = ref(0)
  const skip = ref(0)
  const limit = ref(10)
  const currentPage = ref(1)
  const selectedCaseId = ref("")

  //  Filter Data
  const filterData = reactive({
    entityName: "",
    email: "",
    status: "",
    internalCode: "",
    role: "",
    createdAt: {
      type: "between",
      value: [],
      quickOptionValue: "",
    },
  })

  const { t } = useI18n()

  // Fetch user lists
  async function fetchUserList() {
    try {
      const { data } = await api.getUserList()

      if (!data || data.error) {
        handleErrorForUser({ error: data?.error, $t: t })
      }

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : this.$t("user.errors.listUser"))
    }
  }

  return {
    fetchUserList,
    searchQuery,
    t,
    totalItems,
    skip,
    limit,
    currentPage,
    selectedCaseId,
    ...toRefs(filterData),
  }
}

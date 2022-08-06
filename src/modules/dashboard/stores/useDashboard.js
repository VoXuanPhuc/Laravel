import { defineStore } from "pinia"
import { computed, ref } from "vue"
import { useI18n } from "vue-i18n"
import { handleErrorForUser } from "../api/handleErrorForUser"

const useDashboardStore = defineStore("dashboard", () => {
  const organizationNum = ref(0)
  const taskNum = ref(0)
  const notificationNum = ref(10)
  const userNum = ref(0)

  const statisticList = computed(() => [
    {
      name: "organizations",
      label: t("dashboard.organizations"),
      icon: "OfficeBuilding",
      backgroundColor: "bg-c1-100",
      iconColor: "text-c1-500",
      count: organizationNum.value,
      navigation: {
        name: "ViewOrganizationCreate",
      },
    },
    {
      name: "tasks",
      label: t("dashboard.tasks"),
      icon: "ClipboardCheck",
      backgroundColor: "bg-cWarning-100",
      iconColor: "text-cWarning-500",
      count: taskNum.value,
      navigation: null,
    },
    {
      name: "notifications",
      label: t("dashboard.notifications"),
      icon: "Bell",
      backgroundColor: "bg-cWarning-100",
      iconColor: "text-cWarning-500",
      count: notificationNum.value,
      navigation: null,
    },
    {
      name: "users",
      label: t("dashboard.users"),
      icon: "UserGroup",
      backgroundColor: "bg-cError-100",
      iconColor: "text-c2-400",
      count: userNum.value,
      navigation: {
        name: "ViewUserNew",
      },
    },
  ])

  const { t } = useI18n()

  // Fetch Data
  async function fetchOrganization() {
    organizationNum.value = 100
  }

  async function fetchTasks() {
    taskNum.value = 20
  }

  async function fetchNotifications() {
    notificationNum.value = 33
  }

  async function fetchUsers() {
    userNum.value = 13
  }

  function setErrorMessage(data) {
    handleErrorForUser({ errror: data?.error, $t: t })
  }

  async function fillData() {
    // Notifications
    await fetchNotifications()
    await fetchOrganization()
    await fetchTasks()
    await fetchUsers()
  }

  return {
    statisticList,

    // Fetch data
    fetchNotifications,
    fetchOrganization,
    fetchTasks,
    fetchUsers,

    // Set data
    setErrorMessage,
    fillData,
  }
})

export default useDashboardStore

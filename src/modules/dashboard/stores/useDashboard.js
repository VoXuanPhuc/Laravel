import { defineStore } from "pinia"
import { computed, ref } from "vue"
import { useI18n } from "vue-i18n"
import { handleErrorForUser } from "../api/handleErrorForUser"
import * as systemStatisticApi from "@/readybc/composables/api/apiSystemStatistics"

const useDashboardStore = defineStore("dashboard", () => {
  const organizationNum = ref(0)
  const taskNum = ref(0)
  const notificationNum = ref(0)
  const userNum = ref(0)

  const { t } = useI18n()

  /**
   * Statistic
   */
  const statisticList = computed(() => [
    {
      name: "organizations",
      label: t("dashboard.organizations"),
      icon: "OfficeBuilding",
      backgroundColor: "bg-c1-100",
      iconColor: "text-c1-500",
      count: organizationNum.value,
      navigation: {
        name: "ViewOrganizationNew",
      },
      btnTooltip: {
        text: "New Organization",
        position: "bottom",
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
      btnTooltip: {
        text: "New User",
        position: "bottom",
      },
    },
  ])

  const chartList = ref([
    {
      title: "Monthly top Organisations activities",
      type: "bar",
      data: {
        labels: ["Encoda", "Amz", "Google", "Facebook", "Microsoft"],
        datasets: [
          {
            data: [30, 40, 60, 70, 45],
            backgroundColor: ["#77CEFF", "#0079AF", "#123E6B", "#97B0C4", "#A5C8ED"],
          },
        ],
      },
    },
    {
      title: "Organisations in 6 months",
      type: "line",
      data: {
        labels: ["May", "Jul", "July", "Sep", "October"],
        datasets: [
          {
            data: [30, 40, 60, 70, 45],
            backgroundColor: ["#77CEFF", "#0079AF", "#123E6B", "#97B0C4", "#A5C8ED"],
          },
        ],
      },
    },
    {
      title: "Tasks",
      type: "pipe",
      data: {
        labels: ["Open", "In Progress", "Pending", "Finished", "Cancelled"],
        datasets: [
          {
            data: [30, 40, 60, 70, 45],
            backgroundColor: ["#cff2e3", "#f0cd7a", "#f69cad", "#29996a", "#590817"],
          },
        ],
      },
    },
  ])
  /**
   *
   * @returns
   */
  async function fetchSystemStatisticsData() {
    try {
      const { data } = await systemStatisticApi.fetchSystemStatistics()

      notificationNum.value = data.notificationNum
      organizationNum.value = data.organizationNum
      taskNum.value = data.taskNum
      userNum.value = data.userNum

      return data
    } catch (error) {
      return false
    }
  }

  /**
   *
   * @returns
   */
  async function fetchTenantStatisticsData() {
    try {
      const { data } = await systemStatisticApi.fetchSystemStatistics()

      notificationNum.value = data.notificationNum
      organizationNum.value = data.organizationNum
      taskNum.value = data.taskNum
      userNum.value = data.userNum

      return data
    } catch (error) {
      return false
    }
  }

  /**
   *
   * @param {*} data
   */
  function setErrorMessage(data) {
    handleErrorForUser({ errror: data?.error, $t: t })
  }

  return {
    statisticList,
    chartList,

    // Set data
    setErrorMessage,
    fetchSystemStatisticsData,
    fetchTenantStatisticsData,
  }
})

export default useDashboardStore

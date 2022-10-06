import { defineStore } from "pinia"
import { computed, ref } from "vue"
import { useI18n } from "vue-i18n"
import { handleErrorForUser } from "../api/handleErrorForUser"
import * as systemStatisticApi from "@/readybc/composables/api/apiSystemStatistics"

const useTenantDashboardStore = defineStore("tenantDashboard", () => {
  const taskNum = ref(0)
  const notificationNum = ref(0)
  const userNum = ref(0)
  const activityNum = ref(0)

  const { t } = useI18n()

  const statisticList = computed(() => [
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
      name: "activities",
      label: t("dashboard.activities"),
      icon: "Activity",
      backgroundColor: "bg-cSuccess-100",
      iconColor: "text-c1-500",
      count: activityNum.value,
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
      id: "resourcesInMonths",
      title: "Resources in 6 months",
      type: "line",
      data: {
        labels: [],
        datasets: [
          {
            data: [],
            backgroundColor: ["#77CEFF", "#0079AF", "#123E6B", "#97B0C4", "#A5C8ED"],
          },
        ],
      },
    },
    {
      id: "activitiesByStatus",
      title: "Activities by Status",
      type: "donut",
      data: {
        labels: [],
        datasets: [
          {
            data: [],
            backgroundColor: ["#f69cad", "#f0cd7a", "#29996a", "#3b212b"],
          },
        ],
      },
    },
    {
      id: "resourcesByCategory",
      title: "Resources by Category",
      type: "bar",
      data: {
        labels: [],
        datasets: [
          {
            data: [],
            backgroundColor: ["#77CEFF", "#0079AF", "#123E6B", "#97B0C4"],
          },
        ],
      },
    },
  ])

  /**
   *
   * @returns
   */
  async function fetchTenantStatisticsData() {
    try {
      const { data } = await systemStatisticApi.fetchTenantSystemStatistics()

      if (data) {
        transformStatisticsData(data)
        transformChartData(data)
      }

      return data
    } catch (error) {
      return false
    }
  }

  /**
   *
   * @param {*} response
   */
  function transformStatisticsData(response) {
    userNum.value = response?.userNum
    notificationNum.value = response?.notificationNum
    taskNum.value = response?.taskNum
    activityNum.value = response?.activityNum
  }

  /**
   *
   * @param {*} response
   */
  function transformChartData(response) {
    chartList.value = chartList.value?.map((chartItem) => {
      if (Object.prototype.hasOwnProperty.call(response, chartItem?.id)) {
        const responseData = response[chartItem?.id]

        // Set chart labels
        chartItem.data.labels = responseData?.map((item) => {
          return item.label
        })
        // Set chart datasets

        const valueData = responseData?.map((item) => {
          return item.value
        })

        chartItem.data.datasets[0].data = valueData
      }

      return chartItem
    })
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
    fetchTenantStatisticsData,
  }
})

export default useTenantDashboardStore

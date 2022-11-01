import { defineStore } from "pinia"
import { ref } from "vue"

const useBrightDashboard = defineStore("useBrightDashboard", () => {
  const notiSidebarOpened = ref(true)

  return {
    notiSidebarOpened,
  }
})

export default useBrightDashboard

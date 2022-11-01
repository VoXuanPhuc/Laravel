export const dateHelper = {
  /**
   *
   * @returns
   */
  dayName: () => {
    return new Date().toLocaleDateString("default", { weekday: "long" })
  },

  /**
   *
   * @returns
   */
  date: () => {
    const day = new Date().getDate()

    return dateHelper.getNumberWithOrdinal(day)
  },

  /**
   *
   * @param {*} n
   * @returns
   */
  getNumberWithOrdinal: (n) => {
    const s = ["th", "st", "nd", "rd"]
    const v = n % 100

    return n + (s[(v - 20) % 10] || s[v] || s[0])
  },

  /**
   *
   * @returns
   */
  currentMonth: () => {
    return new Date().toLocaleString("default", { month: "long" })
  },

  dashboardDate() {
    return `${dateHelper.dayName()}, ${dateHelper.currentMonth()} ${dateHelper.date()}`
  },
}

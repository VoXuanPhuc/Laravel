import dayjs from "dayjs"
import router from "@/router"
import { useGlobalStore } from "@/stores/global"

/** Format data */
const formatData = (value, dateFormat) => {
  if (!value) return "-"
  // If dayjs || Date object
  if (dateFormat) {
    const dayObj = dayjs(value)
    if (dayObj.$d.toString() !== "Invalid Date") {
      return dayObj.format(dateFormat)
    }
  }
  return value
}

/** Capitalize */
const capitalize = (str) => {
  return str
    .split(" ")
    .map((word) => {
      const firstChar = word[0].toUpperCase()
      return `${firstChar}${word.substring(1)}`
    })
    .join(" ")
}

/** Goto router name */
const goto = (routerName, { params, query } = {}) => {
  const globalStore = useGlobalStore()
  // Set default language params
  const defaultParams = {
    lang: globalStore.locale || "en",
  }

  router.push({
    name: routerName,
    params: {
      ...defaultParams,
      ...params,
    },
    query,
  })
}

/** Crunch date */
const crunchDate = (what, { type, value }) => {
  if (value === null || value.length === 0) {
    return {}
  } else {
    if (type === "between") {
      return {
        and: [
          {
            [`${what}_gt`]: dayjs(value[0]),
          },
          {
            // add 23 hours 59 minutes
            [`${what}_lt`]: dayjs(value[1]).add(23, "hour").add(59, "minute"),
          },
        ],
      }
    }
    // Type before
    if (type === "before") {
      return {
        [`${what}_lt`]: dayjs(value),
      }
    }
    // Type after
    if (type === "after") {
      return {
        [`${what}_gt`]: dayjs(value).add(23, "hour").add(59, "minute"),
      }
    }
  }
}

export { formatData, capitalize, goto, crunchDate }

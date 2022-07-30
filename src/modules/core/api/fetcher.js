// Factory function returning fetcher suitable for CoverGo GraphQL APIs
import axios from "axios"
import { makeAxiosRestFetcher } from "@covergo/cover-composables"

const baseURL = process.env.VUE_APP_FILE_SYSTEM_ENDPOINT

export const restFetcher = makeAxiosRestFetcher({
  axios,
  baseURL,

  getToken() {
    return localStorage[process.env.VUE_APP_TOKEN_KEY]
  },

  getLocale() {
    return localStorage?.rbcAdminLocale || "en-US"
  },

  debug: true,
})

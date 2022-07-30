// Factory function returning fetcher suitable for CoverGo GraphQL APIs
import axios from "axios"
import { makeAxiosRestFetcher } from "@/readybc/composables/api/makeAxiosRestFetcher"

export const fetcher = makeAxiosRestFetcher({
  url: "/dashboard",
  axios,

  getToken() {
    return localStorage[process.env.VUE_APP_TOKEN_KEY]
  },

  getLocale() {
    return localStorage?.coverAdminLocale || "en-US"
  },

  debug: true,
})

// Factory function returning fetcher suitable for ReadyBC Rest APIs
import axios from "axios"
import { makeAxiosRestFetcher } from "@/readybc/composables/api/makeAxiosRestFetcher"

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

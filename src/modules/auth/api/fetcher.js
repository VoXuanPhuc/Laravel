import { makeAxiosRestFetcher } from "@/readybc/composables/api/makeAxiosRestFetcher"
import axios from "axios"

const baseURL = "https://app-readybc.com"

export const fetcher = makeAxiosRestFetcher({
  baseURL,
  axios,

  getToken() {
    return localStorage[process.env.VUE_APP_TOKEN_KEY]
  },

  getLocale() {
    return localStorage?.rbcAdminLocale || "en-US"
  },

  debug: true,
})

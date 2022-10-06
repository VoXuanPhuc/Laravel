import axios from "axios"
import { defaultErrorHandler } from "../composables/defaultErrorHandler"
import router from "@/router"
import * as helpers from "@/readybc/composables/helpers/helpers"

const fetcher = axios.create({
  baseURL: `https://${window.location.hostname}`,
})

fetcher.defaults.timeout = 3 * 1000 * 60 // 3 mins

fetcher.interceptors.request.use(
  (config) => {
    // Set token, will be replace with data from cookie
    const token = localStorage.getItem(process.env.VUE_APP_TOKEN_KEY) || ""
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }

    /**
     * Appen tenant id to request headers from path to header
     * Check if the route allow to access by lanlord by tenant id first
     *
     */
    const isAbleToAccessByLandlord = router?.currentRoute?.value?.meta?.landlordAccess

    const organizationUid = helpers.getTenantUid()

    if (isAbleToAccessByLandlord && organizationUid) {
      config.headers.organization = organizationUid
    }

    return config
  },
  (error) => {
    // Do something with request error
    return Promise.reject(error)
  }
)

fetcher.interceptors.response.use(
  (response) => {
    // Any status code that lie within the range of 2xx cause this function to trigger
    // Do something with response data
    return response
  },
  (error) => {
    // Any status codes that falls outside the range of 2xx cause this function to trigger
    // Do something with response error
    const centralizedError = defaultErrorHandler(error)
    return Promise.reject(centralizedError)
  }
)

export default fetcher

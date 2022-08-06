import axios from "axios"
import { defaultErrorHandler } from "../composables/defaultErrorHandler"

const fetcher = axios.create({
  baseURL: `${process.env.VUE_APP_BASE_URL}`,
})

fetcher.defaults.timeout = 3 * 1000 * 60 // 3 mins

fetcher.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem(process.env.VUE_APP_TOKEN_KEY) || ""
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
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

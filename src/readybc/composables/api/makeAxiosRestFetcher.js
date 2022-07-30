export function makeAxiosRestFetcher({
  url: fetcherUrl,
  baseURL: fetcherBaseURL,
  axios,

  // Optional but recommended
  getLocale = () => "",
  getToken = () => "",

  // Optional
  timeout = 60000,
  headers: fetcherHeaders = {},
  options: fetcherOptions = {},

  debug = true,
}) {
  if (!axios) throw new Error("Axios dependency is required as a parameter")

  return ({
    url: componentUrl,
    baseURL: componentBaseURL,
    method = "get",
    headers: componentHeaders = {},
    data,
    options: componentOptions = {},
    locale = null,
    token = null,
  }) => {
    // Check to see the fetcher url or component url
    if (!componentUrl && !fetcherUrl) throw new Error("Url is required to make an API call")

    const url = componentUrl || fetcherUrl
    const baseURL = componentBaseURL || fetcherBaseURL

    locale = locale || getLocale()
    token = token || getToken()

    const defaultHeaders = {}
    defaultHeaders["Content-Type"] = "application/json"
    if (locale) defaultHeaders["Accept-Language"] = locale
    if (token) defaultHeaders.Authorization = `Bearer ${token}`

    const headers = { ...defaultHeaders, ...fetcherHeaders, ...componentHeaders } // Headers can be overwritten: Component(Composable) > Fetcher > Default

    const options = { ...fetcherOptions, ...componentOptions }

    // Create Axios instance
    const rbcAxios = axios.create({
      baseURL,
      method,
      headers,
      timeout,
      ...options,
    })

    return new Promise((resolve, reject) => {
      const requestId = Math.random().toString().substr(2, 4)

      if (debug) {
        /* eslint-disable */
        console.groupCollapsed(`%c ReadyBC Request ${requestId}`, "padding: 1px 6px 1px 0px; background: #0256ab; color: white")
        console.log(url)
        console.log(method)
        if (data) console.log(JSON.stringify(data))
        console.log(JSON.stringify(headers, null, 2))
        console.groupEnd()
        /* eslint-enable */
      }

      rbcAxios({ url, data })
        .then((response) => {
          if (debug) {
            /* eslint-disable */
            console.groupCollapsed(
              `%c ReadyBC Response ${requestId}`,
              "padding: 1px 6px 1px 0px; color: #0256ab; background: white"
            )
            console.log(response)
            console.groupEnd()
            /* eslint-enable */
          }

          resolve(response)
        })
        .catch((error) => {
          // eslint-disable-next-line no-console
          console.error("Axios error during fetching", error)
          // eslint-disable-next-line prefer-promise-reject-errors
          reject({
            fetcherErrorCodeResolver: () =>
              error?.status ? { code: "SERVER", message: error?.message } : { code: "NETWORK", message: error?.message },
          })
        })
    })
  }
}

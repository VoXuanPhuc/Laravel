import { validateParams } from "../helpers/validateParams"
import { constructQuery } from "../api/constructQuery"
import { resolveFetcherErrors } from "../api/resolveFetcherErrors"
import { resolveAuthQueryMutationErrors } from "../api/resolveAuthQueryMutationErrors"

export function apiToken(params = {}) {
  validateParams(params)
  const { variables, fragment, fetcher, queryOverride, locale, token } = params

  const data = variables

  const defaultQuery = ""

  const defaultFragment = "/identity/api/v1/login"

  // Make url
  const url = constructQuery({ defaultFragment, defaultQuery, fragment, queryOverride })

  const method = "post"

  return new Promise((resolve) => {
    fetcher({ url, data, method, locale, token })
      .then((response) => {
        resolve({
          data: response?.data,
          response,
          error: resolveAuthQueryMutationErrors({
            type: "query",
            // Have "error" & "errorDescription" instead of "errors" in response?.data
            errors: response?.errors,
            dataToCheck: response?.data?.token,
          }),
        })
      })
      .catch((fetcherError) => {
        resolve(resolveFetcherErrors(fetcherError))
      })
  })
}

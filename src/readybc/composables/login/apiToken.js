import { validateParams } from "../api/validateParams"
import { constructQuery } from "../api/constructQuery"
import { resolveFetcherErrors } from "../api/resolveFetcherErrors"
import { resolveAuthQueryMutationErrors } from "../api/resolveAuthQueryMutationErrors"

export function apiToken(params = {}) {
  validateParams(params)
  const { variables, fragment, fetcher, queryOverride, locale, token } = params

  const defaultQuery = ""

  const defaultFragment = ""

  const query = constructQuery({ defaultFragment, defaultQuery, fragment, queryOverride })

  return new Promise((resolve) => {
    fetcher({ query, variables, locale, token })
      .then((response) =>
        resolve({
          data: response?.data?.token,
          response,
          error: resolveAuthQueryMutationErrors({
            type: "query",
            // Have "error" & "errorDescription" instead of "errors" in response?.data
            errors: response?.errors,
            dataToCheck: response?.data?.token,
          }),
        })
      )
      .catch((fetcherError) => resolve(resolveFetcherErrors(fetcherError)))
  })
}

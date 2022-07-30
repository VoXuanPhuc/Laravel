import { validateParams } from "../api/validateParams"
import { constructQuery } from "../api/constructQuery"
import { resolveFetcherErrors } from "../api/resolveFetcherErrors"
import { resolveAuthQueryMutationErrors } from "../api/resolveAuthQueryMutationErrors"

export function apiForgotPassword(params = {}) {
  validateParams(params)
  const { variables, fragment, fetcher, queryOverride, locale, token } = params

  const defaultQuery = ""

  const defaultFragment = ""

  const query = constructQuery({ defaultFragment, defaultQuery, fragment, queryOverride })

  return new Promise((resolve) => {
    fetcher({ query, variables, locale, token })
      .then((response) =>
        resolve({
          data: response?.data?.forgotPassword,
          response,
          error: resolveAuthQueryMutationErrors({
            type: "mutation",
            statusAndErrors: response?.data?.forgotPassword,
            errors: response?.errors,
          }),
        })
      )
      .catch((fetcherError) => resolve(resolveFetcherErrors(fetcherError)))
  })
}

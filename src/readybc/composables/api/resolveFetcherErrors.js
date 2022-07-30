import { constructError } from "./constructError"

export function resolveFetcherErrors({ fetcherErrorCodeResolver = undefined }) {
  if (!fetcherErrorCodeResolver) throw new Error("There is no 'fetcherErrorCodeResolver' provided")
  const errorObject = fetcherErrorCodeResolver()

  return {
    error: constructError(errorObject),
    data: undefined,
    response: undefined,
  }
}

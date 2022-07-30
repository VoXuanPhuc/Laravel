import { resolveAuthErrors } from "./resolveAuthErrors"
import { resolveQueryErrors } from "./resolveQueryErrors"
import { resolveMutationErrors } from "./resolveMutationErrors"
import { resolveMutationErrorsWithoutStatus } from "./resolveMutationErrorsWithoutStatus"

export function resolveAuthQueryMutationErrors({
  type = undefined, // "query" or "mutation"
  errors = undefined, // from response.errors, for query, mutation, auth
  dataToCheck = undefined, // for query
  statusAndErrors = { status: undefined, errors: undefined, errors_2: undefined }, // for mutation
}) {
  if (!type) throw new Error("No type passed to the errors resolver")

  // 1: First resolve auth; auth errors have higher priority than query errors
  const authError = resolveAuthErrors({ errors })
  if (authError) return authError

  // 2: Resolve query or mutation
  if (type === "query") return resolveQueryErrors({ dataToCheck, errors })
  // if (type === "mutation") return resolveMutationErrors({ statusAndErrors, errors })
  if (type === "mutation") {
    if (statusAndErrors?.status) return resolveMutationErrors({ statusAndErrors, errors })
    return resolveMutationErrorsWithoutStatus({ statusAndErrors, errors })
  }
}

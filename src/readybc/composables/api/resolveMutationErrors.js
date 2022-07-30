import { constructError } from "./constructError"

export function resolveMutationErrors({
  errors = undefined,
  statusAndErrors = { status: undefined, errors: undefined, errors_2: undefined },
}) {
  // Helper function to decide which error to grab first
  const decideErrorMessage = ({ errors, statusAndErrors }) => {
    if (statusAndErrors?.errors_2?.[0]?.message) {
      return statusAndErrors?.errors_2?.[0]?.message
    }

    if (statusAndErrors?.errors?.[0]) {
      return statusAndErrors?.errors?.[0]
    }

    if (errors?.[0]?.message) {
      return errors?.[0]?.message
    }
  }

  // By ReadyBC convention, mutations are failed when status !== success
  if (statusAndErrors?.status !== "success") {
    return constructError({
      code: "QUERY",
      message: decideErrorMessage({ errors, statusAndErrors }),
      userMessage: decideErrorMessage({ errors, statusAndErrors }),
    })
  }

  // If all good
  return undefined
}

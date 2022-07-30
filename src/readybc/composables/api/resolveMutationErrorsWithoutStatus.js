import { constructError } from "./constructError"

export function resolveMutationErrorsWithoutStatus({
  errors = undefined,
  statusAndErrors = { status: undefined, errors: undefined, errors_2: undefined },
}) {
  // Helper function to decide which error to grab first
  const decideErrorMessage = ({ errors, statusAndErrors }) => {
    if (statusAndErrors?.errors_2?.[0]?.message) return statusAndErrors?.errors_2?.[0]?.message
    if (statusAndErrors?.errors?.[0]) return statusAndErrors?.errors?.[0]
    if (errors?.[0]?.message) return errors?.[0]?.message
    return null
  }

  const errorMessage = decideErrorMessage({ errors, statusAndErrors })

  // Throw error if error message exists
  if (errorMessage)
    return constructError({
      code: "QUERY",
      message: errorMessage,
      userMessage: errorMessage,
    })

  // If all good
  return undefined
}

import { constructError } from "./constructError"

export function resolveAuthErrors({ errors }) {
  // 1: Check for auth invalid or expired token
  // Backend is returning specific string to signify invalid or expired token
  if (
    errors?.find((error) => error?.extensions?.code === "authorization" && error?.message?.includes("invalid or expired token"))
  ) {
    return constructError({
      code: "AUTH_INVALID_TOKEN",
    })
  }

  // 2: Check for auth insufficient permissions
  // For inssufficient permissions, the extensions.code is the same but the message doesn't include "invalid or expired token"
  // message is e.g.: `test_uat: You are not authorized to run this. You are missing 'writeIndividuals' permission.`
  // Permissions seem to be applied only for mutations, for read operations it just doesn't return data that user can't access

  if (errors?.find((error) => error?.extensions?.code === "authorization" && error?.message?.includes("permission")))
    return constructError({
      code: "AUTH_INSUFFICIENT_PERMISSIONS",
    })

  // If all good, there is no error
  return undefined
}

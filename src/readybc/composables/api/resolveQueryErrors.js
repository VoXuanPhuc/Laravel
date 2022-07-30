import { constructError } from "./constructError"

export function resolveQueryErrors({ errors = undefined, dataToCheck = undefined }) {
  // Query data checks:
  // 1st Check: dataToCheck cannot be null or undefined
  if (dataToCheck === null || dataToCheck === undefined) {
    return constructError({
      code: "QUERY",
      message: errors?.[0]?.message,
      userMessage: errors?.[0]?.message,
    })
  }

  // 2nd Check: if dataToCheck has list, list cannot be null or undefined
  if (
    Object.prototype.hasOwnProperty.call(dataToCheck, "list") &&
    (dataToCheck.list === null || dataToCheck.list === undefined)
  ) {
    return constructError({
      code: "QUERY",
      message: errors?.[0]?.message,
      userMessage: errors?.[0]?.message,
    })
  }

  // Otherwise all is good
  return undefined
}

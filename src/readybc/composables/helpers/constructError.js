// Sensible defaults for error messages
// By default we are not defining userMessage here as it should be localized, has to happen somewhere else
const dictionary = {
  NETWORK: {
    code: "NETWORK",
    message: "There was a problem with your internet connection.",
    userMessage: undefined,
  }, // Netwrok Error - coming from the fetcher, e.g. offline, timeout

  SERVER: {
    code: "SERVER",
    message: "There was an unexpected problem, please try again later",
    userMessage: undefined,
  }, // Server Error - coming from the fetcher, e.g. status code 500

  AUTH_INVALID_TOKEN: {
    code: "AUTH_INVALID_TOKEN",
    message: "Auth token is expired or invalid",
    userMessage: undefined,
  }, // ReadyBC Auth Error - when token is invalid or expired

  AUTH_INSUFFICIENT_PERMISSIONS: {
    code: "AUTH_INSUFFICIENT_PERMISSIONS",
    message: "Auth token is expired or invalid",
    userMessage: undefined,
  }, // ReadyBC Auth Error - when user doesn't have permissions to do the action

  QUERY: {
    code: "QUERY",
    message: "There was a problem with your GraphQL query",
    userMessage: undefined,
  }, // ReadyBC Query Error - when query or mutation fails for some reason, the message should be populated from the APIs
}

export function constructError({ code = undefined, message = undefined, userMessage = undefined }) {
  if (!code) {
    throw new Error("'code' is required to get error from errors dictionary")
  }

  const errorObject = { ...dictionary?.[code] }

  // If message or userMessage are passed, override what we got from the dictionary
  if (message) {
    errorObject.message = message
  }

  if (userMessage) {
    errorObject.userMessage = userMessage
  }

  return {
    ...errorObject,
  }
}

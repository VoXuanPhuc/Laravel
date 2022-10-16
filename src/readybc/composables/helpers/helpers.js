/**
 *
 * @param {*} tenantData
 */
export const setTenantData = (tenantData) => {
  window.$cookies.set("tenantUid", tenantData?.uid)
  window.$cookies.set("tenantName", tenantData?.name)
}

/**
 *
 * @returns
 */
export const getTenantData = () => {}

/**
 *
 * @returns
 */
export const getTenantUid = () => {
  return window.$cookies.get("tenantUid")
}

/**
 *
 * @param {*} token
 * @returns
 */
export const parseJwt = (token) => {
  if (!token || token.length <= 0) {
    return ""
  }

  const base64Url = token.split(".")[1]
  const base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/")

  const jsonPayload = decodeURIComponent(
    window
      .atob(base64)
      .split("")
      .map(function (c) {
        return "%" + ("00" + c.charCodeAt(0).toString(16)).slice(-2)
      })
      .join("")
  )

  return JSON.parse(jsonPayload)
}

/**
 *
 * @returns
 */
export const getUserFullName = () => {
  const jwtIdToken = localStorage.getItem(process.env.VUE_APP_ID_TOKEN_KEY)

  const tokenData = parseJwt(jwtIdToken)

  return [tokenData?.given_name || tokenData?.first_name, tokenData?.family_name || tokenData?.last_name].join(" ")
}

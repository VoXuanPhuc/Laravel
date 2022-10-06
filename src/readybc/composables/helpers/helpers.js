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

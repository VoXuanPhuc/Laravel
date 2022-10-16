import fetcher from "@/modules/core/api/fetcher"

/**
 *
 * @param {*} uid
 * @returns
 */
export const fetchDepedencyFactors = async () => {
  return fetcher.get(`identity/api/v1/dependencies/factors`)
}

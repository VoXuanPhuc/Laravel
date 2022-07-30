/**
 *
 * @param {*} fn from CoverComposables
 * @param {*} params params that fn would normally accept
 * @example e.g. apiCases({fetcher, variables: {limit: 10}}) --> apiPromise(apiCases, {fetcher, variables: {limit: 10}})
 */
export async function apiPromise(fn, params) {
  if (!fn) throw new Error("No 'fn' provided, did you forget to pass an api function from CoverComposables?")
  const { data, error } = await fn(params)
  return error ? Promise.reject(error) : Promise.resolve(data)
}

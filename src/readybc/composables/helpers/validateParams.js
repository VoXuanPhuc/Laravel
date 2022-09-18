export function validateParams(params = {}) {
  const { fetcher } = params

  // If no fetcher
  // eslint-disable-next-line no-console
  if (!fetcher) {
    console.error("Did you forget to pass 'fetcher' into ReadyBC ?")
  }
}

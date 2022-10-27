import fetcher from "@/modules/core/api/fetcher"

export const apiUploadFile = (payload) => {
  const { data, options } = payload

  const config = options ?? {}
  config.headers = {
    "Content-Type": "multipart/form-data",
  }
  return fetcher.post("identity/api/v1/edocs", data, config)
}

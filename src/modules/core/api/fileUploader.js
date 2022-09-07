import fetcher from "@/modules/core/api/fetcher"

export const apiUploadFile = (payload) => {
  const { data } = payload

  return fetcher.post("identity/api/v1/edocs", data, {
    headers: {
      "Content-Type": "multipart/form-data",
    },
  })
}

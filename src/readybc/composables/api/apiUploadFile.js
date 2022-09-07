import fetcher from "@/modules/core/api/fetcher"

const apiUploadFile = async function (uploadData) {
  const { url, data, options } = uploadData
  return fetcher.post("identity/api/v1/edocs")
}

export { apiUploadFile }

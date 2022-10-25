import fetcher from "@/modules/core/api/fetcher"

const apiUploadFile = async function (uploadData) {
  return fetcher.post("identity/api/v1/edocs")
}

const apiDeleteFile = async function (uid) {
  return fetcher.delete(`identity/api/v1/edocs/${uid}`)
}

export { apiUploadFile, apiDeleteFile }

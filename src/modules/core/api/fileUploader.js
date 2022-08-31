import fetcher from "./fetcher"

export const apiUploadFile = () => {
  return fetcher.post("/document")
}

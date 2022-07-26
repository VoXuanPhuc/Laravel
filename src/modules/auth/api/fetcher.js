import axios from "axios"

const fetcher = axios.create({
  baseURL: "https://app-readybc.com/identity/",
  timeout: 300,
})

export { fetcher }

export const getToken = () => {
  return fetcher.get("login")
}

import fetcher from "@/modules/core/api/fetcher"
/**
 *
 * @param {*} data
 * @param {*} fileName
 */
const downloadFromBlob = (data, fileName, ext) => {
  // Create blob url from data
  const url = window.URL.createObjectURL(new Blob([data]))

  // Create an anchor
  const link = document.createElement("a")
  link.href = url

  link.setAttribute("download", `${fileName || "Unknown"}_${Date.now()}.${ext}`)

  // Add link to html and trigger click
  document.body.appendChild(link)
  link.click()
}

/**
 *
 * @param {*} url
 * @param {*} fileName
 */
const downloadFromUrl = async (url, fileName) => {
  debugger
  const data = await fetcher.get(url, {
    responseType: "blob",
  })

  debugger
  // Create blob url from data
  const downloadUrl = window.URL.createObjectURL(new Blob([data]))

  // Create an anchor
  const link = document.createElement("a")
  link.href = downloadUrl

  link.setAttribute("download", `${fileName || "Unknown"}_${Date.now()}`)

  // Add link to html and trigger click
  document.body.appendChild(link)
  link.click()
}

export { downloadFromBlob, downloadFromUrl }

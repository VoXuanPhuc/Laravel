/**
 *
 * @param {*} file
 */
export const fileIcon = (file) => {
  if (isPDF(file)) {
    return "PDF"
  }
  if (isTxt(file)) {
    return "TXT"
  }

  if (isWord(file)) {
    return "OfficeWord"
  }

  if (isExcel(file)) {
    return "OfficeExcel"
  }

  if (isPPT(file)) {
    return "OfficePPT"
  }

  if (isCSV(file)) {
    return "CSV"
  }

  return "Document"
}

export const isOffice = (file) => {
  return isWord(file) || isExcel(file) || isPPT(file)
}

// File type
export const isImage = (file) => {
  return file?.mime_type?.includes("image/")
}
export const isPDF = (file) => {
  return file?.mime_type?.includes("application/pdf")
}

export const isTxt = (file) => {
  return file?.mime_type?.includes("text/plain")
}

export const isCSV = (file) => {
  return file?.mime_type?.includes("text/csv")
}

export const isWord = (file) => {
  return file?.mime_type?.includes("msword")
}

export const isExcel = (file) => {
  return file?.mime_type?.includes("spreadsheet")
}

export const isPPT = (file) => {
  return file?.mime_type?.includes("presentation")
}

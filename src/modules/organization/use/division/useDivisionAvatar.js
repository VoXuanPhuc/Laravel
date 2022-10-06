import isEmpty from "lodash.isempty"

/**
 *
 * @param {*} name
 * @param {*} color
 * @returns
 */
export const generateAvatar = (name, color) => {
  // Only generate if the name with 2 letters
  if (isEmpty(name) || name.length < 2 || isEmpty(color)) {
    return ""
  }

  color = color.replace("#", "")

  const avartarLetter = name.substr(0, 2)

  return `https://ui-avatars.com/api/?name=${avartarLetter}&background=${color}&color=fff`
}

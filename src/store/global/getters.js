export default {
  /**
   * We get variants for components
   * We have to juggle situation when we are using the same components in the Studio as in actual Apps we are building
   * For this reason, Studio has defined theme on window
   * Apps will have defined theme in store
   */
  variants: () => (componentName, variant) => {
    if (window) {
      return window?.COVER_ADMIN_THEME?.baseComponentsVariants?.[componentName]?.[variant] || { el: {} }
    }

    return { el: {} }
  },
}

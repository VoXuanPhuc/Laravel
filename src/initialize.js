import router from "./router"
import { useGlobalStore } from "./stores/global"
import { i18n } from "@/setups/i18nConfig"
import * as configHelper from "@/readybc/composables/helpers/configHelper"

const initializeVariants = async ({ tenantId, filename }) => {
  let defaultVariants
  let tenantVariants

  try {
    defaultVariants = await import("@/setups/variants/")
    defaultVariants = defaultVariants?.variants
    // eslint-disable-next-line no-empty
  } catch (error) {
    console.groupCollapsed("%c Default variant file does not exist", "padding: 1px 6px 1px 0px; background: yellow; color: black")
    console.log(`tenantId: ${tenantId}`)
    console.log(error)
    console.groupEnd()
    return defaultVariants
  }

  try {
    tenantVariants = await import(`@/tenants/${tenantId}/variants/${filename}`)
    tenantVariants = tenantVariants?.variants
    const dynamicKeys = Object.keys(tenantVariants)
    // override default variants based on passed in variant from tenant
    // example: if tenant variant has EcButton object with "primary-lg" variant. It will only override the "primary-lg" variant in JButton
    dynamicKeys.map((key) => (defaultVariants[key] = { ...defaultVariants[key], ...tenantVariants[key] }))
    return defaultVariants
  } catch (error) {
    console.groupCollapsed("%c Tenant variant file does not exist", "padding: 1px 6px 1px 0px; background: yellow; color: black")
    console.log(`tenantId: ${tenantId}`)
    console.log(error)
    console.groupEnd()
    return defaultVariants
  }
}

// eslint-disable-next-line no-unused-vars
const initializeRoutes = async ({ routes = [] }) => {
  const overwriteRoot = routes.find((route) => route.path === "/")
  if (overwriteRoot) {
    // eslint-disable-next-line no-unused-expressions
    router?.removeRoute(router?.options?.routes?.[0])
  }
  routes.forEach((route) => {
    // eslint-disable-next-line no-unused-expressions
    router?.addRoute(route)
  })
}

/**
 * Initialize theme
 * @param {*} param0
 */
const initializeTheme = async ({ tenantId, filename }) => {
  try {
    await import("./assets/css/globalTheme.css")
    await import("./assets/css/style.css")
    await import(`@/tenants/${tenantId}/assets/${filename}`) // duplicate variables will override existing
    // eslint-disable-next-line no-empty
  } catch (error) {
    console.groupCollapsed("%c Tenant theme file does not exist", "padding: 1px 6px 1px 0px; background: yellow; color: black")
    console.log(`tenantId: ${tenantId}`)
    console.log(error)
    console.groupEnd()
  }
}

/**
 * Initialize locale
 */
const initializeLocales = async () => {
  const globalStore = useGlobalStore()

  // Set preferred locales based on what's in localStorage
  globalStore.setLocale(window.localStorage?.readyBCAdminLocale || "en-US")
}

/**
 * Register new vuex modules or override existing ones
 * @param {*} param0
 */
const initializeStore = async ({ tenantId }) => {
  try {
    const module = await import(`@/tenants/${tenantId}/store/index.js`)
    const moduleNames = Object.keys(module?.default)
    // const moduleObjects = Object.values(module?.default)
    moduleNames.forEach((name, index) => {
      // store.registerModule(name, {
      //   namespaced: true,
      //   ...moduleObjects[index],
      // })
    })
  } catch (error) {
    console.groupCollapsed("%c Tenant store file does not exist", "padding: 1px 6px 1px 0px; background: yellow; color: black")
    console.log(`tenantId: ${tenantId}`)
    console.log(error)
    console.groupEnd()
  }
}

/**
 * Initialize by configs
 * @param {*} param0
 * @returns
 */
const initializeTenant = async () => {
  const globalStore = useGlobalStore()

  console.log("%c Initializing", "padding: 1px 6px 1px 0px; background: green; color: white")

  // Fetch config based on tenantId and clientId
  const config = await configHelper.buildConfigs()

  /**
   * Save tenantId and clientId in store and in localStorage
   * Rule of thumb is to access tenantId/clientId from store as
   * for some other auth strategies they will not be accessible in localStorage
   */

  const tenantId = config?.server?.tenantId || "default"
  const clientId = "0781150b-454e-11ed-af08-0242ac120005"

  globalStore.setTenantId(tenantId)
  globalStore.setClientId(clientId)

  /**
   * Save settings in store
   */
  globalStore.setTenantSettings(config)

  /**
   * Get tenant data
   */
  const themeFilename = config?.default?.theme_filename
  const variantFilename = config?.default?.variant_filename
  const routes = config?.default?.routes
  const dateFormat = config?.default?.dateFormat
  const dateTimeFormat = config?.default?.dateTimeFormat

  if (dateFormat) {
    globalStore.setDateFormat(dateFormat)
  }
  if (dateTimeFormat) {
    globalStore.setDateTimeFormat(dateTimeFormat)
  }

  /**
   * Initialize theme, routes, locales
   */
  await initializeStore({ tenantId })
  await initializeTheme({ tenantId, filename: themeFilename })
  await initializeRoutes({ routes })
  await initializeLocales()

  // initialize variants and return to use in main.js
  // also return i18n we initialized
  return { variants: await initializeVariants({ tenantId, filename: variantFilename }), i18n }
}

export { initializeTenant }

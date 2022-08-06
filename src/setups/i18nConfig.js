import { createI18n } from "vue-i18n"
import enUS from "@/locales/en-US"
import { useGlobalStore } from "@/stores/global"

const locale = "en-US"
const loadedLanguages = []

// Create i18n
const i18n = createI18n({
  locale, // set locale
  fallbackLocale: "en-US",
  messages: {
    "en-US": enUS,
  }, // set locale messages
})

export function setI18nLanguage(lang) {
  i18n.global.locale = lang
  window.localStorage.setItem("readyBCAdminLocale", lang)
  return lang
}

export function loadLanguageAsync(lang) {
  const globalStore = useGlobalStore()

  // <-- If language is already loaded -->
  if (loadedLanguages.includes(lang)) {
    // If the same language
    if (i18n.global.locale.value === lang) return lang
    return Promise.resolve(setI18nLanguage(lang))
  }

  // <-- Else start loading deafult & tenant locale -->
  const tenantId = globalStore.getTenantId ?? null
  let defaultMessages = {}
  let tenantMessages = {}

  // Dynamic import of locales
  const defaultPromise = import(`@/locales/${lang}.js`)
  const tenantPromist = import(`@/tenants/${tenantId}/locales/${lang}.js`)
  const promises = [defaultPromise]
  if (tenantId) {
    promises.push(tenantPromist)
  }
  return Promise.allSettled(promises).then(([defaultResult, tenantResult]) => {
    /**
     * Default Locale File
     */
    if (defaultResult.status === "fulfilled") {
      defaultMessages = defaultResult.value?.default ?? {}
    }

    /**
     * Tenant locale file
     * Exist => Save locale & Override default locale
     * Not exist => Throw log errors
     */
    if (tenantResult.status === "fulfilled") {
      tenantMessages = tenantResult.value?.default ?? {}

      // Override tenant locale to default locale (forEach modules)
      Object.keys(tenantMessages).forEach((key) => {
        const defaultValue = defaultMessages?.[key] ?? {}
        const tenantValue = tenantMessages?.[key] ?? {}
        defaultMessages[key] = { ...defaultValue, ...tenantValue }
      })
    } else if (tenantResult.status === "rejected") {
      /* eslint-disable no-console */
      console.groupCollapsed("%c Tenant Locale file does not exist", "padding: 1px 6px 1px 0px; background: yellow; color: black")
      console.log(`tenantId: ${tenantId}`)
      console.log(`Locale: ${lang}`)
      console.log(tenantResult.reason?.code)
      console.groupEnd()
      /* eslint-enable no-console */
    }

    /**
     * If either default or tenant locale exists, setup language.
     */
    if (defaultResult.status === "fulfilled" || tenantResult.status === "fulfilled") {
      i18n.global.setLocaleMessage(lang, defaultMessages)
      loadedLanguages.push(lang)
      return setI18nLanguage(lang)
    }
  })
}

export { i18n }

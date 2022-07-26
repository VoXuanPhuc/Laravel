import { defaultErrorHandler } from "@/modules/core/composables/defaultErrorHandler.js"

export function handleErrorForUser({ error = {}, $t }) {
  defaultErrorHandler({ error, $t })
}

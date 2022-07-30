import { defaultErrorHandler } from "@/modules/core/composables/defaultErrorHandler"

export function handleErrorForUser({ error = {}, $t }) {
	defaultErrorHandler({ error, $t })
}

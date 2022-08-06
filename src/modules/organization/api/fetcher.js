// Factory function returning fetcher suitable for CoverGo GraphQL APIs
import axios from "axios"
import { makeAxiosGraphQLFetcher } from "@covergo/cover-composables"

export const fetcher = makeAxiosGraphQLFetcher({
	url: process.env.VUE_APP_COVERGO_GRAPHQL,
	axios,

	getToken() {
		return localStorage[process.env.VUE_APP_TOKEN_KEY]
	},

	getLocale() {
		return localStorage?.coverAdminLocale || "en-US"
	},

	debug: true,
})

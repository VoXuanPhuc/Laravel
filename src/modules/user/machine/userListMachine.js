export const userListMachine = {
	id: "userList",
	initial: "idle",
	states: {
		idle: {
			on: {
				FETCH: "reading",
			},
		},
		reading: {
			id: "read",
			initial: "fetching",
			states: {
				fetching: {
					invoke: {
						id: "readUserService",
						src: "readUserData",
						onDone: { target: "dataListing", actions: "setResults" },
						onError: { target: "failure", actions: "setErrorMessage" },
					},
				},
				dataListing: {
					on: {
						FILTER: "fetching",
					},
				},
				failure: { on: { RETRY: "fetching" } },
			},
		},
	},
}

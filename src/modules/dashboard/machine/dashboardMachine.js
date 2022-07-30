export const dashboardMachine = {
  id: "dashboard",
  initial: "idle",
  states: {
    idle: {
      on: {
        FETCH: "reading",
      },
    },
    reading: {
      id: "read",
      type: "parallel",
      states: {
        readingClient: {
          id: "readClient",
          initial: "fetching",
          states: {
            fetching: {
              invoke: {
                id: "readClientService",
                src: "readClient",
                onDone: { target: "success", actions: "setClientNumber" },
                onError: { target: "failure", actions: "setErrorMessage" },
              },
            },
            success: { type: "final" },
            failure: { on: { RETRY: "fetching" } },
          },
        },
        readingPolicy: {
          id: "readPolicy",
          initial: "fetching",
          states: {
            fetching: {
              invoke: {
                id: "readPolicyService",
                src: "readPolicy",
                onDone: { target: "success", actions: "setPolicyNumber" },
                onError: { target: "failure", actions: "setErrorMessage" },
              },
            },
            success: { type: "final" },
            failure: { on: { RETRY: "fetching" } },
          },
        },
        readingCase: {
          id: "readCase",
          initial: "fetching",
          states: {
            fetching: {
              invoke: {
                id: "readCaseService",
                src: "readCase",
                onDone: { target: "success", actions: "setCaseNumber" },
                onError: { target: "failure", actions: "setErrorMessage" },
              },
            },
            success: { type: "final" },
            failure: { on: { RETRY: "fetching" } },
          },
        },
      },
    },
  },
}

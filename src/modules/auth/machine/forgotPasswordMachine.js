export const forgotPasswordMachine = {
  id: "forgotPassword",
  initial: "formFilling",
  states: {
    formFilling: {
      on: {
        SEND_MAIL: "fetching",
        BACK_TO_LOGIN: "backToLogin",
      },
    },
    fetching: {
      invoke: {
        id: "sendMailService",
        src: "sendMail",
        onDone: "success",
        onError: "failure",
      },
    },
    success: {
      on: { BACK_TO_LOGIN: "backToLogin" },
    },
    failure: {
      on: { BACK_TO_LOGIN: "backToLogin" },
    },
    backToLogin: {
      type: "final",
      entry: "backToLogin",
    },
  },
}

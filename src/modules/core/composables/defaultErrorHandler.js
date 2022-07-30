import store from "@/store"

function logout() {
  store.dispatch("logout")
}

export function defaultErrorHandler({ error = {}, $t }) {
  const { code, userMessage } = error

  if (code === "NETWORK_OR_SERVER") {
    store.dispatch("addToastMessage", {
      type: "error",
      content: {
        type: "message",
        text: $t("errors.network"),
      },
    })
    return
  }

  if (code === "AUTH_INVALID_TOKEN") {
    store.dispatch("addToastMessage", {
      type: "error",
      content: {
        type: "message",
        text: $t("errors.token"),
      },
    })

    setTimeout(() => {
      logout()
    }, 1500)
    return
  }

  if (code === "AUTH_INSUFFICIENT_PERMISSIONS") {
    store.dispatch("addToastMessage", {
      type: "error",
      content: {
        type: "message",
        text: $t("errors.permission"),
      },
    })
    return
  }

  if (code === "QUERY") {
    store.dispatch("addToastMessage", {
      type: "error",
      content: {
        type: "message",
        text: `${$t("errors.query")} ${userMessage}`,
      },
    })
    return
  }

  store.dispatch("addToastMessage", {
    type: "error",
    content: {
      type: "message",
      text: $t("errors.general"),
    },
  })
}

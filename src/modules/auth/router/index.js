import ViewLogin from "../views/ViewLogin.vue"
import ViewForgotPassword from "../views/ViewForgotPassword.vue"
import ViewNewPassword from "../views/ViewNewPassword.vue"
import ViewConfirmForgotPassword from "@/modules/auth/views/ViewConfirmForgotPassword"

export default [
  {
    path: "/",
    component: ViewLogin,
    name: "DefaultLogin",
    props: true,
    meta: {
      title: "Login",
      isPublic: true,
    },
  },
  {
    path: "/login",
    component: ViewLogin,
    name: "ViewLogin",
    props: true,
    meta: {
      title: "Login",
      isPublic: true,
    },
  },

  {
    path: "/forgot-password",
    component: ViewForgotPassword,
    name: "ViewForgotPassword",
    props: true,
    meta: {
      title: "Forgot Password",
      isPublic: true,
    },
  },
  {
    path: "/confirm-forgot-password",
    component: ViewConfirmForgotPassword,
    name: "ViewConfirmForgotPassword",
    props: true,
    meta: {
      title: "Confirm Forgot Password",
      isPublic: true,
    },
  },
  {
    path: "/new-password",
    component: ViewNewPassword,
    name: "ViewNewPassword",
    props: true,
    meta: {
      title: "New Password",
      isPublic: true,
    },
  },
]

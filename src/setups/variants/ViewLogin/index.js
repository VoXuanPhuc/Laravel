export default {
  default: "primary",
  primary: {
    el: {
      root: "flex items-center",
      title: "mb-6 lg:text-4xl",
      subtitle: {
        class: "text-c1-200 mb-12",
        tenantId: "text-cWhite",
        warning: "text-cError-500",
      },
      form: "w-full max-w-md",
      email: {
        class: "mb-5",
        variant: "primary-lg",
        isDark: true,
      },
      password: {
        class: "mb-12",
        variant: "primary-lg",
        isDark: true,
      },
      login: {
        class: "mr-5",
        variant: "primary",
      },
      forgotPassword: {
        class: "",
        variant: "transparent",
      },
    },
  },
}

export default {
  default: "primary",
  primary: {
    el: {
      root: "flex items-center",
      title: "tmb-6 lg:text-5xl text-c0-50",
      subtitle: {
        class: "text-c1-200 mb-12",
        tenantId: "text-cWhite",
        warning: "text-cError-500",
      },
      form: "w-full max-w-md",
      email: {
        class: "mb-5 text-c0-50",
        variant: "primary-lg",
        isDark: true,
      },
      password: {
        class: "mb-12 text-c0-50",
        variant: "primary-lg",
        isDark: true,
      },
      login: {
        class: "mr-5 hover:bg-c3-50 hover:border-c3-50 hover:text-c0-50 hover:shadow-lg",
        variant: "primary",
      },
      forgotPassword: {
        class: "hover:bg-c3-50 hover:border-c3-50 hover:text-c0-50",
        variant: "transparent",
      },
    },
  },
}

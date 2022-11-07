export default {
  default: "primary",
  primary: {
    el: {
      root: "flex items-center text-cBlack",
      title: "tmb-6 font-bold lg:text-4xl text-cBlack",
      readyTitle: "font-bold",
      bcTitle: "font-bold text-c1-800",

      subtitle: {
        class: "text-cBlack mb-12 text-[18px] mt-4 text-c4-300",
        tenantId: "",
        warning: "text-cBlack-500",
      },
      form: "w-full max-w-md",
      email: {
        class: "mb-5",
        variant: "primary-lg",
        isDark: false,
      },
      password: {
        class: "mb-12",
        variant: "primary-lg",
        isDark: false,
      },
      login: {
        class: "w-full bg-c1-800 h-12   justify-center text-base  hover:border-c3-50 hover:text-c0-50 hover:shadow-lg",
        variant: "primary",
      },
      forgotPassword: {
        wrapper: "justify-end",
        class: " text-c1-800 hover:border-c3-50 !hover:text-c1-800 mb-8 hover:cursor-pointer text-base",
        variant: "transparent",
        iconSuffix: "!hover:cursor-pointer",
      },
    },
  },
}

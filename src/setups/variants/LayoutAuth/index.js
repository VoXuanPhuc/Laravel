export default {
  default: "primary",
  primary: {
    el: {
      root: "flex-col min-h-screen lg:flex-row",
      left: {
        wrapper: "lg:items-center items-center lg:w-6/12  ",
        container: "flex-col h-full w-full items-center justify-center",
        desktop: {
          bg: "hidden md:block h-full w-full text-c4-200 bg-login-pattern bg-no-repeat bg-cover",
          img: "h-56",
        },
        mobile: {
          container: "flex-col h-full w-full items-center justify-center mt-4 mb-4",
          bg: "md:hidden  h-full w-full text-c4-200 bg-c5-100",
          img: "h-24",
        },
      },
      svgs: {
        wrapper: "relative px-5 pt-4 flex-grow flex-col sm:px-12 lg:justify-center lg:px-16 lg:w-6/12 xl:pl-32 xl:pr-0",
        mobile: {
          class: "absolute left-0 text-c1-800 lg:hidden",
          style: "bottom: 99%; z-index: -1",
        },
        desktop: {
          class: "absolute top-0 text-c1-800 hidden lg:block",
          style: "left: 99%; z-index: -1",
        },
      },
      background: {
        class: "absolute w-full h-full left-0 top-0 bg-cWhite",
        style: "z-index: -2",
      },
    },
  },
}

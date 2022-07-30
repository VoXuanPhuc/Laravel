export default {
  default: "primary",
  primary: {
    el: {
      root: "flex-col min-h-screen lg:flex-row-reverse",
      image: {
        wrapper: "p-10 lg:items-center items-center lg:w-7/12",
        img: "mx-auto w-auto w-72",
      },
      svgs: {
        wrapper:
          "relative bg-c1-800 px-5 pt-4 pb-12 text-cWhite flex-grow flex-col sm:px-12 lg:justify-center lg:px-16 lg:w-5/12 xl:pl-32 xl:pr-0",
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

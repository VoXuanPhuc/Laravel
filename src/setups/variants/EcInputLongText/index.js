export default {
  default: "primary",
  primary: {
    el: {
      root: "text-base bg-cWhite title-2 text-c0-500 rounded-md border border-c4-100 px-5 py-3 appearance-none w-full min-h-10 placeholder-c0-500 focus:outline-none focus:outline-none focus:border-c1-500",
    },
  },
  transparent: {
    el: {
      root: "bg-cTransparent title-2 text-c0-500 rounded-md appearance-none w-full min-h-10 placeholder-c0-500 focus:outline-none focus:outline-none focus:border-c1-500",
    },
  },
  disabled: {
    el: {
      root: "bg-c0-200 bg-opacity-25 title-2 text-c0-500 rounded-md border border-c0-300 px-5 py-3 appearance-none w-full min-h-10 placeholder-c0-500 focus:outline-none cursor-pointer pointer-events-none",
    },
  },
}

export default {
  default: "primary",
  primary: {
    description: "You can describe what it is",
    el: {
      root: "text-sm bg-c0-50 text-cBlack rounded-md leading-tight border border-c0-300 px-5 appearance-none w-full h-8 placeholder-c0-500 focus:outline-none focus:border-c1-500",
    },
  },
  "primary-lg": {
    el: {
      root: "bg-c0-50 shadow text-cBlack text-lg rounded-md leading-tight border border-c0-300 px-5 appearance-none w-full h-12 placeholder-c0-500 focus:outline-none focus:border-c1-500",
    },
  },
  "primary-disabled": {
    description: "Disabled input",
    el: {
      root: "text-sm bg-c0-50 text-cBlack rounded-md leading-tight border border-c0-300 px-5 appearance-none w-full h-8 placeholder-c0-500 focus:outline-none focus:border-c1-500 cursor-not-allowed",
    },
  },
}
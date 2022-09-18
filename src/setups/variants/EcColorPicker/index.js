export default {
  default: "primary",
  primary: {
    el: {
      root: "flex items-center border border-c0-200 focus:border-c1-500 focus:shadow relative rounded-lg w-full h-10",
      input:
        "flex items-center leading-tight w-full h-full py-2 pl-4 pr-8 text-c0-500 select-none cursor-pointer focus:outline-none focus:shadow-outline",
      enterFrom: "opacity-0",
      enterActive: "transition transition-medium transition-ease",
      leaveActive: "transition transition-medium transition-ease-out",
      leaveTo: "opacity-0",
    },
  },
}

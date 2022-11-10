export default {
  default: "primary",
  primary: {
    el: {
      root: "text-base flex items-center border border-c4-100 focus:border-c1-500 focus:shadow relative rounded-lg w-full h-8",
      input:
        "flex items-center leading-tight w-full h-full py-2 pl-4 pr-8 text-c0-500 select-none cursor-pointer focus:outline-none focus:shadow-outline",
      enterFrom: "opacity-0",
      enterActive: "transition transition-medium transition-ease",
      leaveActive: "transition transition-medium transition-ease-out",
      leaveTo: "opacity-0",
    },
    componentInstanceVariants: {
      calendar: "default",
    },
  },
}

export default {
  default: "primary",
  primary: {
    el: {
      root: "w-full flex",
      day: "flex-grow md:w-auto md:mb-0 mr-2 focus:outline-none",
      month: "flex-grow md:w-auto md:mb-0 sm:mr-2 focus:outline-none",
      year: "flex-grow md:w-auto md:mb-0 focus:outline-none",
    },
    componentInstanceVariants: {
      daySelect: "default",
      monthSelect: "default",
      yearSelect: "default",
    },
  },
}

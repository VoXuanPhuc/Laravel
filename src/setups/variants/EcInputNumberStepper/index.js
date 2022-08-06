export default {
  default: "primary",
  primary: {
    el: {
      root: "flex flex-auto",
      button:
        "flex items-center justify-center bg-c2-600 hover:bg-c2-400 hover:text-cWhite text-cBlack rounded cursor-pointer h-10 w-10 select-none focus:outline-none",
      buttonDisabled:
        "flex items-center justify-center bg-c2-600 text-cBlack rounded cursor-not-allowed pointer-events-none  h-10 w-10 select-none focus:outline-none",
      counter: "flex items-center justify-center text-cBlack h-10 w-16 select-none",
    },
  },
}

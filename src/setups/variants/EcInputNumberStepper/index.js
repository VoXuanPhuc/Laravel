export default {
  default: "primary",
  primary: {
    el: {
      root: "flex flex-auto",
      button:
        "flex items-center justify-center bg-c1-600 hover:bg-c1-800 hover:text-cWhite text-cWhite rounded cursor-pointer h-8 w-10 select-none focus:outline-none",
      buttonDisabled:
        "flex items-center justify-center bg-c1-600 text-cWhite rounded cursor-not-allowed pointer-events-none  h-8 w-10 select-none focus:outline-none",
      counter: "flex items-center justify-center text-cBlack text-center h-8 w-16 select-none",
    },
  },
}

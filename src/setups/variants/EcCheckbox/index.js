export default {
  default: "primary",
  primary: {
    el: {
      root: "inline-block cursor-pointer",
      hiddenInput: "w-0 h-0 opacity-0 absolute",
      checkbox: "flex items-center w-6 h-6 rounded border border-c1-200 cursor-pointer focus:outline-none",
      checkboxChecked:
        "text-pink-500 flex items-center w-6 h-6 rounded bg-pink-100 border border-c1-200 cursor-pointer focus:outline-none pl-1",
    },
    assets: {
      iconChecked: "&#x2713;",
      iconIndeterminate: "&#8259;",
    },
  },
  disabled: {
    el: {
      root: "inline-block cursor-pointer",
      hiddenInput: "w-0 h-0 opacity-0 absolute",
      checkbox:
        "s-pointer-events-none s-cursor-not-allowed pointer-events-none  flex items-center w-6 h-6 rounded border bg-c2-500 border-c2-500 cursor-pointer focus:outline-none focus:border-c2-400",
      checkboxChecked:
        "s-pointer-events-none s-cursor-not-allowed pointer-events-none  text-c2-500 flex items-center w-6 h-6 rounded bg-c2-500 border border-c2-500 cursor-pointer focus:outline-none focus:border-c2-400",
    },
    assets: {
      iconChecked: "&#x2713;",
      iconIndeterminate: "&#8259;",
    },
  },
}

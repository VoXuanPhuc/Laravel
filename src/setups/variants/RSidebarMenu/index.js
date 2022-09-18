export default {
  default: "primary",
  primary: {
    el: {
      root: "flex-col overflow-y-auto",
      buttons: "relative overflow-y-auto",
      button: {
        for: "mt-2 relative",
        wrapper: "px-4 lg:px-6 py-3 rounded-lg cursor-pointer",
        idle: "",
        selected: "bg-cWhite",
        box: "inline-flex lg:flex items-center justify-end md:justify-start select-none",
        icon: {
          wrapper: "flex-shrink-0 flex-grow-0",
          idle: "text-cWhite",
          selected: "text-c1-800",
        },
        text: {
          wrapper: "font-medium tracking-wider leading-tight text-sm truncate",
          idle: "text-cWhite",
          selected: "text-c1-800",
        },
      },
    },
  },
}

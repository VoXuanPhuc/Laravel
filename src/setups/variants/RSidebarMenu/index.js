export default {
  default: "primary",
  primary: {
    el: {
      root: "flex-col overflow-y-auto",
      buttons: "relative overflow-y-auto",
      button: {
        for: "mt-2 relative text-c1-800",
        wrapper: "px-4 lg:px-6 py-3 rounded-lg cursor-pointer",
        idle: "",
        selected: "text-cWhite bg-c1-800 text-base ",
        box: "inline-flex lg:flex items-center justify-end md:justify-start select-none",
        icon: {
          wrapper: "flex-shrink-0 flex-grow-0",
          idle: "text-c3-100",
          selected: "text-cWhite",
        },
        text: {
          wrapper: "font-medium tracking-wider leading-tight text-base truncate",
          idle: "text-c3-100",
          selected: "text-cWhite",
        },
      },
      subMenu: {
        wrapper: "mx-auto mr-0",
        text: {
          wrapper: "font-medium tracking-wider leading-tight text-base truncate",
          idle: "text-c3-100",
          selected: "text-cWhite",
        },
      },
    },
  },
}

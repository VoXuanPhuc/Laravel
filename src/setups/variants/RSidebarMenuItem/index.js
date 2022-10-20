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
        selected: "bg-c1-800",
        box: "inline-flex lg:flex items-center justify-end md:justify-start select-none",
        icon: {
          wrapper: "flex-shrink-0 flex-grow-0",
          idle: "text-c3-100",
          selected: "text-cWhite",
        },
        text: {
          wrapper: "font-medium tracking-wider leading-tight text-sm truncate",
          idle: "text-c3-100",
          selected: "text-cWhite",
        },
      },
      subMenu: {
        wrapper: "mx-auto mr-0",
        text: {
          wrapper: "font-medium tracking-wider leading-tight text-sm truncate",
          idle: "text-c3-100",
          selected: "text-cWhite",
        },
      },

      data: {
        wrapper: "mx-auto mr-0",
        text: {
          wrapper: "font-medium tracking-wider leading-tight text-sm truncate",
          idle: "text-c1-800  text-xs bg-c1-100 pl-2 pr-2 rounded-lg",
          selected: "text-c1-800 text-xs  bg-c1-100 pl-2 pr-2 rounded-lg",
        },
      },
    },
  },
}

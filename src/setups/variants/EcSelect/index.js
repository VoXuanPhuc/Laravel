export default {
  default: "primary",
  primary: {
    el: {
      root: "text-base block relative",
      select:
        "bg-cWhite text-c0-500 rounded-md leading-tight border border-c4-100 pl-5 pr-8 appearance-none w-full h-8 placeholder-c0-500 focus:outline-none focus:border-c1-500",
      iconRoot: "pointer-events-none absolute inset-y-0 right-0 flex items-center text-c0-300 px-2",
      icon: "",
      disabledOption: "bg-cError-100 cursor-pointer",
    },
    assets: {
      iconName: "ChevronDown",
    },
  },
}

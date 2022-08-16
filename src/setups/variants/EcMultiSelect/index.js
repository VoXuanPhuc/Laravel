export default {
  default: "primary",
  primary: {
    el: {
      root: "flex text-sm items-center rounded-lg bg-cWhite border border-c0-300 w-full",
      tagRoot: "flex flex-wrap w-full",
      tag: "px-2 py-1 m-1 rounded bg-c1-800 text-cWhite",
      tagSingle: "px-2 py-1 m-1 rounded bg-c1-800 text-cWhite w-full flex justify-between",
      tagRemove: "ml-2",
      input: "bg-c0-100 border-b border-c0-300 apperance-none px-5 w-full h-10 focus:outline-none",
      placeholder: "text-c0-500 px-5 pointer-events-none",
      options: "bg-cWhite border border-c0-300 shadow",
      optionsStyles: "max-height: 12rem; top: 100%; left: -1px; right: -1px",
      option: "text-cBlack select-none hover:bg-c1-500 hover:text-cWhite px-5 py-2",
      selectedOption: "bg-c1-700 text-cWhite select-none px-5 py-2",
      noOption: "px-5 py-2",
      transition: {
        enterFrom: "opacity-0",
        leaveTo: "opacity-0",
        enterActive: "transition transition-medium transition-ease",
        leaveActive: "transition transition-short transition-ease-out",
      },
    },
  },
}

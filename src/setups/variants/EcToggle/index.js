export default {
  default: "primary",
  primary: {
    el: {
      root: "flex items-center",
      sliderTrue: "relative w-12 h-6 rounded-full inline-block cursor-pointer bg-c0-100 focus:outline-none",
      sliderFalse: "relative w-12 h-6 rounded-full inline-block cursor-pointer bg-c0-100 focus:outline-none",
      switchTrue: "absolute w-6 h-6 bg-cSuccess-500 rounded-full inline-block",
      switchFalse: "absolute w-6 h-6 bg-c0-300 rounded-full inline-block",
      labelTrue: "ml-2 text-cSuccess-500",
      labelFalse: "ml-2 text-c0-300",
    },
  },
  "primary-sm": {
    el: {
      root: "flex items-center",
      sliderTrue: "relative w-8 h-4 rounded-full inline-block cursor-pointer bg-c0-100 focus:outline-none",
      sliderFalse: "relative w-8 h-4 rounded-full inline-block cursor-pointer bg-c0-100 focus:outline-none",
      switchTrue: "absolute w-4 h-4 bg-cSuccess-500 rounded-full inline-block",
      switchFalse: "absolute w-4 h-4 bg-c0-300 rounded-full inline-block",
      labelTrue: "ml-1 text-sm text-cSuccess-500",
      labelFalse: "ml-1 text-sm text-c0-300",
    },
  },
}

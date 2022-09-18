export default {
  default: "primary",
  primary: {
    el: {
      calendar: "absolute py-4 left-0 z-10 w-80 border border-c0-300 shadow rounded bg-cWhite",
      navigation: "mb-3 flex justify-center items-center",
      changeMonthButtons: "flex-0 mx-1 text-xs p-1 cursor-pointer rounded-full select-none hover:bg-c1-500 hover:text-cWhite",
      weekdaysWrapper: "flex justify-between px-4 py-2 mb-2 text-xs uppercase text-c0-300 text-center",
      weekday: "",
      monthYearSelect: "flex-1 mx-1 p-1 bg-cTransparent text-cBlack rounded border border-c0-300 focus:outline-none",
      datesWrapper: "flex flex-wrap justify-between px-4",
      dateWrapper: "relative flex justify-center my-1",
      date: "w-8 h-8 text-c0-300 border border-cTransparent rounded-full hover:text-c1-500 hover:border-c1-500 focus:outline-none",
      dateRangeBetween: "absolute w-full left-0 h-6 bg-c1-200 z-0",
      dateRangeStart: "absolute top-0 w-4 h-6 bg-c1-200 z-0",
      dateRangeEnd: "absolute top-0 w-4 h-6 bg-c1-200 z-0",
      dateOfActiveMonth:
        "w-8 h-8 text-cBlack border border-cTransparent rounded-full hover:text-c1-500 hover:border-c1-500 focus:outline-none",
      selectedDate: "w-8 h-8 bg-c1-500 rounded-full text-cWhite focus:outline-none",
      disabledDate: "w-8 h-8 text-c0-300 pointer-events-none",
    },
  },
}

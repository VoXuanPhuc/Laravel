export default {
	default: "primary",
	primary: {
		el: {
			root: "fixed bottom-0 left-0 p-4 w-full max-w-md leading-tight",
			messageSuccess: "relative w-full p-5 rounded-lg shadow inline-block bg-cSuccess-500 text-cWhite mb-2 focus:outline-none",
			messageError: "relative w-full p-5 rounded-lg shadow inline-block bg-cError-500 text-cWhite mb-2 focus:outline-none",
			messageWarning: "relative w-full p-5 rounded-lg shadow inline-block bg-cWarning-500 text-cWhite mb-2 focus:outline-none",
			messageNeutral: "relative w-full p-5 rounded-lg shadow inline-block bg-cWhite text-cBlack mb-2 focus:outline-none",
			enterFrom: "opacity-0",
			enterActive: "transition-all duration-300 ease",
			leaveActive: "transition-all duration-300 ease-out",
			leaveTo: "opacity-0",
			closeIcon: "absolute top-0 right-0 mt-1 mr-1 cursor-pointer select-none p-1 rounded-full hover:bg-c1-200 hover:text-cBlack",
		},
	},
}

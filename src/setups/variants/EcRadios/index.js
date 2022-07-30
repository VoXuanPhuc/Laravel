export default {
	default: "primary",
	primary: {
		el: {
			root: "text-sm flex flex-auto flex-wrap select-none",
			item:
				"tracking-wider rounded-full px-4 py-1 mr-3 mb-3 bg-cWhite text-c0-500 border border-c0-500 cursor-pointer text-center leading-snug focus:outline-none",
			itemSelected:
				"font-medium tracking-wider rounded-full px-4 py-1 mr-3 mb-3 bg-c1-500 text-cWhite border border-c1-500 cursor-pointer text-center leading-snug focus:outline-none",
		},
	},
	rounded: {
		el: {
			root: "text-sm flex flex-auto select-none",
			item:
				"rounded-full px-5 py-3 bg-cTransparent text-c0-500 mr-2 font-medium text-sm leading-tight cursor-pointer focus:outline-none hover:bg-c1-500 hover:text-cWhite",
			itemSelected: "rounded-full px-5 py-3 bg-c1-500 mr-2 text-cWhite font-medium text-sm leading-tight focus:outline-none",
		},
	},
}

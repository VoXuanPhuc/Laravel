import { disableBodyScroll, enableBodyScroll, clearAllBodyScrollLocks } from "body-scroll-lock"

export default (app) => {
  // Click outside
  app.directive("click-outside", {
    beforeMount(el, binding, vNode) {
      if (typeof binding.value !== "function") {
        const componentName = vNode.context.name
        let warn = `[Vue-click-outside:] provided expression '${binding.expression}' is not a function, but has to be`
        if (componentName) {
          warn += `Found in component '${componentName}'`
        }

        // eslint-disable-next-line
        console.warn(warn)
      }
      // Define Handler and cache it on the element
      const bubble = binding.modifiers.bubble
      const handler = (e) => {
        if (bubble || (!el.contains(e.target) && el !== e.target)) {
          binding.value(e)
        }
      }

      el.__vueClickOutside__ = handler

      document.addEventListener("click", handler)
    },

    unmounted(el) {
      document.removeEventListener("click", el.__vueClickOutside__)
      el.__vueClickOutside__ = null
    },
  })

  // Scroll Lock
  app.directive("scroll-lock", {
    mounted(el, binding) {
      if (binding.value) {
        disableBodyScroll(el, { reserveScrollBarGap: true })
      }
    },
    updated(el, binding) {
      clearAllBodyScrollLocks()
      if (binding.value) {
        disableBodyScroll(el, { reserveScrollBarGap: true })
      } else {
        enableBodyScroll(el, { reserveScrollBarGap: true })
      }
    },
    unmounted(el) {
      enableBodyScroll(el, { reserveScrollBarGap: true })
    },
  })
}

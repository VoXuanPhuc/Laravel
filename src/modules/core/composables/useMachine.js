/**
 * xState useMachine fn translated from typescript to js
 * official xstate/vue npm package has number of flaws
 *  - it is using old vue composables
 *  - it is not properly working in snowpack
 */

import { shallowRef, watch, isRef, onMounted, onBeforeUnmount } from "vue"
import { interpret, State } from "xstate"

var __rest =
  (this && this.__rest) ||
  function (s, e) {
    var t = {}
    for (const p in s) {
      if (Object.prototype.hasOwnProperty.call(s, p) && e.indexOf(p) < 0) {
        t[p] = s[p]
      }
    }
    if (s != null && typeof Object.getOwnPropertySymbols === "function") {
      for (var i = 0, p = Object.getOwnPropertySymbols(s); i < p.length; i++) {
        if (e.indexOf(p[i]) < 0 && Object.prototype.propertyIsEnumerable.call(s, p[i])) {
          t[p[i]] = s[p[i]]
        }
      }
    }
    return t
  }
export function useMachine(machine, options = {}) {
  const { context, guards, actions, activities, services, delays, state: rehydratedState } = options
  const interpreterOptions = __rest(options, ["context", "guards", "actions", "activities", "services", "delays", "state"])
  const machineConfig = {
    context,
    guards,
    actions,
    activities,
    services,
    delays,
  }
  const createdMachine = machine.withConfig(machineConfig, Object.assign(Object.assign({}, machine.context), context))
  const service = interpret(createdMachine, interpreterOptions).start(rehydratedState ? State.create(rehydratedState) : undefined)
  const state = shallowRef(service.state)
  onMounted(() => {
    service.onTransition((currentState) => {
      if (currentState.changed) {
        state.value = currentState
      }
    })
    state.value = service.state
  })
  onBeforeUnmount(() => {
    service.stop()
  })
  return { state, send: service.send, service }
}
export function useService(service) {
  const serviceRef = isRef(service) ? service : shallowRef(service)
  const state = shallowRef(serviceRef.value.state)
  watch(
    serviceRef,
    (service, _, onCleanup) => {
      state.value = service.state
      const { unsubscribe } = service.subscribe((currentState) => {
        if (currentState.changed) {
          state.value = currentState
        }
      })
      onCleanup(() => unsubscribe())
    },
    {
      immediate: true,
    }
  )
  const send = (event) => serviceRef.value.send(event)
  return { state, send, service: serviceRef }
}

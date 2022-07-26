import { createStore } from "vuex"
import globalStore from "./global"

const store = createStore({
  ...globalStore,
  modules: {},
})

export default store

import ViewDependencyScenarioList from "@/modules/dependency/views/dependency/ViewDependencyScenarioList"
import ViewDependencyScenarioNew from "@/modules/dependency/views/dependency/ViewDependencyScenarioNew"
import ViewDependencyScenarioDetail from "@/modules/dependency/views/dependency/ViewDependencyScenarioDetail"

export default [
  {
    path: "/dependency-scenarios",
    component: ViewDependencyScenarioList,
    name: "ViewDependencyScenarioList",
    props: true,
    meta: {
      module: "dependency",
    },
  },

  {
    path: "/dependency-scenarios/new",
    component: ViewDependencyScenarioNew,
    name: "ViewDependencyScenarioNew",
    props: true,
    meta: {
      isPublic: true,
      module: "dependency",
    },
  },

  {
    path: "/dependency-scenarios/:uid",
    component: ViewDependencyScenarioDetail,
    name: "ViewDependencyScenarioDetail",
    props: true,
    meta: {
      isPublic: true,
      module: "dependency",
    },
  },
]

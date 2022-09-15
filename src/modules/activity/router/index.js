import ViewActivityList from "../views/ViewActivityList"
import ViewActivityNew from "../views/ViewActivityNew"
import ViewActivityNewStep2 from "../views/ViewActivityNewStep2"
import ViewActivityNewStep3 from "../views/ViewActivityNewStep3"

export default [
  {
    path: "/activities",
    component: ViewActivityList,
    name: "ViewActivityList",
    props: true,
    meta: {
      module: "activity",
    },
  },

  {
    path: "/activities/new",
    component: ViewActivityNew,
    name: "ViewActivityNew",
    props: true,
    meta: {
      isPublic: true,
      module: "activity",
    },
  },

  {
    path: "/activities/new/step-2",
    component: ViewActivityNewStep2,
    name: "ViewActivityNewStep2",
    props: true,
    meta: {
      isPublic: true,
      module: "activity",
    },
  },

  {
    path: "/activities/new/step-3",
    component: ViewActivityNewStep3,
    name: "ViewActivityNewStep3",
    props: true,
    meta: {
      module: "activity",
      isPublic: true,
    },
  },
]

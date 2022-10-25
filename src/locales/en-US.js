import core from "@/modules/core/locales/en-US"
import auth from "@/modules/auth/locales/en-US"
import dashboard from "@/modules/dashboard/locales/en-US"
import organization from "@/modules/organization/locales/en-US"
import activity from "@/modules/activity/locales/en-US"
import resource from "@/modules/resource/locales/en-US"
import industry from "@/modules/industry/locales/en-US"
import user from "@/modules/user/locales/en-US"
import setting from "@/modules/setting/locales/en-US"
import supplier from "@/modules/supplier/locales/en-US"
import dependency from "@/modules/dependency/locales/en-US"
import businessContinuityPlan from "@/modules/bcp/locales/en-US"

export default {
  ...core,
  ...auth,
  ...dashboard,
  ...organization,
  ...activity,
  ...resource,
  ...industry,
  ...user,
  ...setting,
  ...supplier,
  ...dependency,
  ...businessContinuityPlan,

  errors: {
    system: "System error, please contact administrator",
    network: "Network Error: Please try again.",
    token: "Please login again.",
    permission: "You are not authorized to do this action.",
    notFound: "Not Found",
    query: "There was a problem.",
    general: "Oops, some errors occured.",
  },
}

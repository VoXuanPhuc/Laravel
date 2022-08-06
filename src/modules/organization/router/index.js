import ViewOrganizationList from "../views/ViewOrganizationList.vue"
import ViewOrganizationDetail from "../views/ViewOrganizationDetail.vue"
import ViewOrganizationCreate from "../views/ViewOrganizationCreate.vue"

export default [
	{
		path: "/organization",
		component: ViewOrganizationList,
		name: "ViewOrganizationList",
		props: true,
		meta: {
			module: "organization",
		},
	},
	{
		path: "/organization/:organizationId",
		component: ViewOrganizationDetail,
		name: "ViewOrganizationDetail",
		props: true,
		meta: {
			module: "organization",
		},
	},
	{
		path: "/organization/create",
		component: ViewOrganizationCreate,
		name: "ViewOrganizationCreate",
		props: true,
		meta: {
			module: "organization",
		},
	},
]

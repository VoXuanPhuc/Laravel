<template>
  <RLayout>
    <EcFlex class="flex-wrap justify-center w-full">
      <EcBox class="w-full max-w-3xl mb-10">
        <!-- Headline -->
        <EcFlex class="justify-between w-full mb-4 item-center">
          <EcHeadline as="h3" variant="h3" class="capitalize"> {{ $t("core.update") }} {{ currentRole.name }} </EcHeadline>

          <div>
            <EcButton variant="tertiary" @click="$router.back()">{{ $t("core.back") }}</EcButton>
          </div>
        </EcFlex>

        <!-- List of permissions -->
        <EcBox class="w-full p-4 border rounded border-c0-200 bg-cWhite">
          <!-- Loading -->
          <EcSpinner v-if="isLoading" type="dots" />
          <!-- Multi select box for permissions -->
          <div
            v-for="permissionGroup in computedPermissionsByGroup"
            :key="permissionGroup.id"
            class="flex items-center justify-between mt-4 mb-4"
          >
            <span>{{ permissionGroup.groupName }}</span>
            <EcBox class="w-7/12">
              <EcMultiSelect
                :options="permissionGroup.availablePermissions"
                :modelValue="permissionGroup.associatedPermissions"
                @update:modelValue="
                  handlePermissionInputChange({
                    permissionGroupId: permissionGroup.groupId,
                    changedPermissions: $event,
                  })
                "
              />
            </EcBox>
          </div>
        </EcBox>
      </EcBox>
    </EcFlex>
  </RLayout>
</template>

<script>
import { useRoleDetail } from "../use/useRoleDetail"
import { usePermissions } from "../use/usePermissions"

export default {
  name: "ViewRoleDetail",
  props: {
    /**
     * Id of Role user wants to update
     */
    uid: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      /**
       * List of all available permissions
       * Is fetched once in created and is cached here
       */
      allPermissionsByGroup: [],

      /**
       * Permission group that users is currently updating
       */

      currentRole: {
        permissions: [],
      },

      isLoading: false,
    }
  },
  setup() {
    const { getRoleDetail, roleAddPermission, roleRemovePermission } = useRoleDetail()
    const { getPermissionsWithGroup } = usePermissions()

    return {
      getRoleDetail,
      getPermissionsWithGroup,
      roleAddPermission,
      roleRemovePermission,
    }
  },
  computed: {
    // Transform all permission by group to display
    computedPermissionsByGroup() {
      // Go through list of all permissions
      // Find if particular permissions is assigned to currentRole
      // If it is, append property isSelected=true so that we can show it's added
      // Also figure out what targetIds permission has so that we know if it's "all" or "" version
      return this.allPermissionsByGroup.map((permissionGroup) => {
        // Find if permission from allPermissionsByGroup is in the currentRole
        return {
          groupId: permissionGroup.id,
          groupName: permissionGroup.name,
          groupDesc: permissionGroup.description,
          availablePermissions: this.transformAvailableOptions(permissionGroup.permissions),
          associatedPermissions: this.currentRole?.permissions
            .filter((associatedPermission) => {
              return associatedPermission.group_id === permissionGroup.id
            })
            .map((associatedPermission) => {
              return {
                uid: associatedPermission.uid,
                name: associatedPermission.label,
                value: associatedPermission.name,
              }
            }),
        }
      })
    },
  },
  mounted() {
    this.fetchRoleDetail()
    this.fetchPermissions()
  },
  methods: {
    // Get Role detail
    async fetchRoleDetail() {
      this.isLoading = true

      const { uid } = this.$route.params

      this.currentRole = await this.getRoleDetail(uid)

      this.isLoading = false
    },

    // Fetch permissions with group
    async fetchPermissions() {
      this.isLoading = true
      this.allPermissionsByGroup = await this.getPermissionsWithGroup()
      this.isLoading = false
    },

    // Handle Permission multiple selectbox value change
    async handlePermissionInputChange({ permissionGroupId, changedPermissions }) {
      this.isLoading = true

      const associatedPermissionsByGroup = this.associatedPermissionsByGroup(permissionGroupId)
      const associatedPermissionsByGroupIds = this.associatedPermissionsByGroup(permissionGroupId).map(
        (associatedPermission) => associatedPermission.uid
      )

      // =========== Check if the user has added permission item to group

      const addedPermissionItems = changedPermissions.filter((changedItem) => {
        return !associatedPermissionsByGroupIds.includes(changedItem.uid)
      })

      // If there has an added permission item then send request to server
      if (addedPermissionItems.length > 0) {
        await this.roleAddPermission(this.currentRole.uid, addedPermissionItems[0].uid)
      }

      // ========= Check if the user has removed permission item out of group
      const selectedPermissionIds = changedPermissions.map((changedItem) => changedItem.uid)
      const removedPermissionItems = associatedPermissionsByGroup.filter((associatedPermission) => {
        return !selectedPermissionIds.includes(associatedPermission.uid)
      })

      if (removedPermissionItems.length > 0) {
        await this.roleRemovePermission(this.currentRole.uid, removedPermissionItems[0].uid)
      }

      await this.fetchRoleDetail()
      this.isLoading = false
    },

    // To name => value array for multiple selectbox
    transformAvailableOptions(permissions) {
      const output = permissions.map((permission) => {
        return {
          uid: permission.uid,
          name: permission.label,
          value: permission.name,
        }
      })

      return output
    },

    // Associated permission by group
    associatedPermissionsByGroup(permissionGroupId) {
      return this.currentRole?.permissions.filter((associatedPermission) => {
        return associatedPermission.group_id === permissionGroupId
      })
    },
  }, // End methods
}
</script>

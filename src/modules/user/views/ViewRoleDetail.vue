<template>
  <RLayout>
    <EcFlex class="flex-wrap justify-center w-full">
      <EcBox class="w-full max-w-3xl mb-10">
        <!-- Headline -->
        <EcFlex class="justify-between w-full mb-4 item-center">
          <EcHeadline as="h2" variant="h2" class="capitalize">{{ $t("update") }} {{ currentPermissionGroup.name }}</EcHeadline>
          <div>
            <EcButton variant="tertiary" @click="$router.back()">{{ $t("back") }}</EcButton>
          </div>
        </EcFlex>

        <!-- List of permissions -->
        <EcBox class="w-full p-4 border rounded border-c0-200 bg-cWhite">
          <div v-for="permission in computedPermissions" :key="permission.id" class="flex items-center justify-between mb-4">
            <span>{{ permission.id ? permission.id : permission.name }}</span>
            <EcBox class="w-7/12">
              <EcMultiSelect
                :options="permission.options"
                :modelValue="permission.targetType"
                @update:modelValue="handleInput({ permission: permission, payload: $event })"
              />
            </EcBox>
          </div>

          <!-- Loading -->
          <EcSpinner v-if="isLoading" type="dots" />
        </EcBox>
      </EcBox>
    </EcFlex>
  </RLayout>
</template>

<script>
// import {
//   apiPermissions,
//   apiPermissionGroups,
//   apiAddPermissionToPermissionGroup,
//   apiRemovePermissionFromPermissionGroup,
// } from "@covergo/cover-composables"
import { handleErrorForUser } from "../api"
export default {
  name: "ViewPermissionGroupDetail",
  props: {
    /**
     * Id of permissionGroup user wants to update
     */
    id: {
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
      allPermissions: [],

      /**
       * Permission group that users is currently updating
       */

      currentPermissionGroup: {},

      isLoading: false,
    }
  },
  computed: {
    computedPermissions() {
      // Go through list of all permissions
      // Find if particular permissions is assigned to currentPermissionGroup
      // If it is, append property isSelected=true so that we can show it's added
      // Also figure out what targetIds permission has so that we know if it's "all" or "" version
      return this.allPermissions.map((permission) => {
        // Find if permission from allPermissions is in the currentPermissionGroup
        const found = this.currentPermissionGroup.targettedPermissions?.find((item) => item.permission.id === permission.id)

        return {
          id: permission.id,
          name: permission.name,
          description: permission.description,
          options: this.decideOptionsAvailable(permission.targetIds),
          targetType: found?.targetIds?.map((item) => ({ name: item, value: item })),
        }
      })
    },
  },
  mounted() {
    // this.fetchPermissions()
    // this.fetchPermissionsOfGroup()
  },
  methods: {
    async fetchPermissions() {
      // this.isLoading = true
      // const { error, data } = await apiPermissions({ fetcher })

      // if (error) {
      //   handleErrorForUser({ error, $t: this.$t })
      //   this.isLoading = false
      //   return
      // }

      // this.allPermissions = data.filter((item) => !["clientId", "groups", "role"].includes(item.id))
      this.isLoading = false
    },

    async fetchPermissionsOfGroup() {
      // const variables = {
      //   where: { id: this.id },
      // }
      // this.isLoading = true
      // const { error, data } = await apiPermissionGroups({ variables, fetcher })
      // this.isLoading = false

      // if (error) {
      //   handleErrorForUser({ error, $t: this.$t })
      //   this.isLoading = false
      //   return
      // }

      // this.currentPermissionGroup = data[0]
      this.isLoading = false
    },
    async addPermission(permissionId, targetId) {
      // const variables = {
      //   id: this.id,
      //   permissionId,
      //   targetId,
      // }
      // const { error } = await apiAddPermissionToPermissionGroup({ variables, fetcher })

      const error = null
      if (error) {
        handleErrorForUser({ error, $t: this.$t })
        this.isLoading = false
      }
    },
    async removePermission(permissionId, targetId) {
      // const variables = {
      //   id: this.id,
      //   permissionId,
      //   targetId,
      // }
      // const { error } = await apiRemovePermissionFromPermissionGroup({ variables, fetcher })
      const error = null
      if (error) {
        handleErrorForUser({ error, $t: this.$t })
        this.isLoading = false
      }
    },
    async handleInput({ permission, payload }) {
      // targetIds of the current permission
      // const currentTargetIds = this.currentPermissionGroup?.targettedPermissions?.find(
      //   (item) => item.permission.id === permission.id
      // )?.targetIds
      // const selectedIds = payload.map((item) => item.value)
      // if (!currentTargetIds) {
      //   await this.addPermission(permission.id, selectedIds?.[0])
      // } else {
      //   // compare two arrays above by getting difference in two arrays
      //   // get the added or removed item
      //   const removedValue = currentTargetIds?.filter((x) => !selectedIds?.includes(x))
      //   const addedValue = selectedIds?.filter((x) => !currentTargetIds?.includes(x))
      //   if (removedValue?.length > 0) {
      //     console.log("removing permission")
      //     await this.removePermission(permission.id, removedValue[0])
      //   } else if (addedValue?.length > 0) {
      //     console.log("adding permission")
      //     await this.addPermission(permission.id, addedValue[0])
      //   }
      // }
      // this.fetchPermissionsOfGroup()
    },
    decideOptionsAvailable(targetIds) {
      const output = [
        {
          name: "{creatorRights}",
          value: "{creatorRights}",
        },
        {
          name: "{entityId}",
          value: "{entityId}",
        },
      ]

      if (targetIds?.includes("all")) {
        output.push({
          name: "all",
          value: "all",
        })
      }

      return output
    },
  },
}
</script>

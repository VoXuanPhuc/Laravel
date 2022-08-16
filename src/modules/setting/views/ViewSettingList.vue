<template>
  <RLayout :title="$t('setting.settings')" class="bg-c0-100">
    <RLayoutTwoCol :isLoading="isLoading">
      <template #left>
        <EcBox variant="card-1" class="pt-8">
          <EcHeadline as="h2" variant="h2" class="px-5 text-c1-800">
            {{ $t("setting.accountInfo") }}
          </EcHeadline>
          <EcBox class="mt-4">
            <REditableField
              class="p-0 cursor-pointer"
              v-for="item in accountInformation"
              :key="item.key"
              :label="item.label"
              :value="item.value"
              :canDelete="false"
              :canEdit="item.key === 'fullName'"
              @edit="decideUpdateModal(item)"
            />
          </EcBox>
          <EcButton variant="primary-sm" class="uppercase py-3 mt-3" @click="openPasswordModal()">
            {{ $t("setting.changePassword") }}
          </EcButton>
        </EcBox>
        <EcBox variant="card-1" class="mt-8">
          <EcHeadline as="h2" variant="h2" class="px-5 text-c1-800">
            {{ $t("setting.language") }}
          </EcHeadline>
          <EcBox class="mt-4">
            <REditableField
              :label="$t('setting.language')"
              :value="dictionary?.[currentLocale]"
              :canEdit="false"
              :canDelete="false"
              class="cursor-pointer"
              @click="openLangModal()"
            />
          </EcBox>
        </EcBox>
      </template>
    </RLayoutTwoCol>

    <!-- Update Modal  -->
    <teleport to="#layer2">
      <EcModalSimple zIndex="50" variant="center-3xl" :isVisible="isEntityModalOpen" @overlay-click="closeModalAndClearForms()">
        <RFormInput v-model="updateEntityObj.englishFirstName" componentName="EcInputText" label="First Name" class="mb-6" />
        <RFormInput v-model="updateEntityObj.englishLastName" componentName="EcInputText" label="Last Name" class="mb-6" />
        <EcFlex>
          <EcButton @click="handleUpdateEntity" class="mr-2">
            {{ $t("core.update") }}
          </EcButton>
          <EcButton variant="tertiary-outline" @click="closeModalAndClearForms()">
            {{ $t("core.cancel") }}
          </EcButton>
        </EcFlex>
      </EcModalSimple>
    </teleport>
  </RLayout>

  <!-- Change language -->
  <teleport to="#layer2">
    <EcModalSimple :isVisible="isLangModalOpen" variant="center-2xl" @overlay-click="closeLangModal()">
      <!-- Modal Header -->
      <EcBox class="mb-6">
        <EcText class="text-c0-300 font-medium">
          {{ $t("setting.editFor") }}
        </EcText>
        <EcHeadline as="h2" variant="h2" class="text-c1-500 mt-2">
          {{ $t("setting.language") }}
        </EcHeadline>
      </EcBox>

      <!-- Modal form -->
      <EcBox class="w-full mb-10">
        <RFormInput
          v-model="selectedLocale"
          class="mt-4"
          componentName="EcSelect"
          :placeholder="$t('setting.pleaseSelect')"
          :options="options"
          :label="$t('setting.displayLanguage')"
        />
      </EcBox>

      <EcFlex>
        <EcButton class="mr-4" variant="primary" @click="handleUpdateLocale()">
          {{ $t("setting.update") }}
        </EcButton>
        <EcButton variant="tertiary-outline" @click="closeLangModal()">
          {{ $t("setting.cancel") }}
        </EcButton>
      </EcFlex>
    </EcModalSimple>
  </teleport>

  <!-- Change password -->
  <teleport to="#layer2">
    <EcModalSimple :isVisible="isPasswordModalOpen" variant="center-2xl" @overlay-click="closeAndClearModalPassord()">
      <!-- Modal Header -->
      <EcBox class="mb-6">
        <EcText class="text-c0-300 font-medium">
          {{ $t("setting.editFor") }}
        </EcText>
        <EcHeadline as="h2" variant="h2" class="text-c1-500 mt-2">
          {{ $t("setting.password") }}
        </EcHeadline>
      </EcBox>

      <!-- Modal form -->
      <EcBox class="w-full mb-10">
        <RFormInput
          v-model="formPassword.currentPassword"
          class="mt-4"
          componentName="EcInputText"
          :label="$t('setting.currentPassword')"
          :validator="v"
          field="formPassword.currentPassword"
          @update:modelValue="onFormValueChanged('currentPassword')"
          type="password"
        />
        <RFormInput
          v-model="formPassword.newPassword"
          class="mt-4"
          componentName="EcInputText"
          :label="$t('setting.newPassword')"
          :validator="v"
          field="formPassword.newPassword"
          @update:modelValue="onFormValueChanged('newPassword')"
          type="password"
        />
        <RFormInput
          v-model="formPassword.confirmPassword"
          class="mt-4"
          componentName="EcInputText"
          :label="$t('setting.confirmPassword')"
          :validator="v"
          field="formPassword.confirmPassword"
          @update:modelValue="onFormValueChanged('confirmPassword')"
          type="password"
        />
      </EcBox>

      <EcFlex>
        <EcButton class="mr-4" variant="primary" @click="handleUpdatePassword()">
          {{ $t("setting.update") }}
        </EcButton>
        <EcButton variant="tertiary-outline" @click="closeAndClearModalPassord()">
          {{ $t("setting.cancel") }}
        </EcButton>
      </EcFlex>
    </EcModalSimple>
  </teleport>
</template>
<script>
import { computed, nextTick, readonly, ref, onMounted } from "vue"
// import { useStore } from "vuex"
import { useI18n } from "vue-i18n"
import { fetcher, handleErrorForUser } from "../api"
import { requiredIf, minLength } from "@vuelidate/validators"
import useVuelidate from "@vuelidate/core"
import { useGlobalStore } from "@/stores/global"

export default {
  name: "ViewSettingList",

  setup() {
    const { locale, t } = useI18n()
    // const store = useStore()
    const globalStore = useGlobalStore()
    const isEntityModalOpen = ref(false)
    const isPasswordModalOpen = ref(false)
    const selectedLocale = ref(null)
    const isLangModalOpen = ref(false)
    const updateEntityObj = ref(null)
    const isLoading = ref(false)
    const me = ref(null)
    const formPassword = ref({
      currentPassword: null,
      newPassword: null,
      confirmPassword: null,
    })

    onMounted(() => {
      getMe()
    })

    const getMe = async () => {
      isLoading.value = true

      // const { data, error } = await apiMe({ fetcher, fragment })
      const data = [{}]
      const error = null
      if (error) handleErrorForUser({ error, $t: t })
      else me.value = data
      isLoading.value = false
    }

    // Validate
    const rules = {
      formPassword: {
        currentPassword: {
          requiredIf: requiredIf(() => isPasswordModalOpen?.value),
        },
        newPassword: {
          requiredIf: requiredIf(() => isPasswordModalOpen?.value),
          minLength: minLength(8),
        },
        confirmPassword: {
          requiredIf: requiredIf(() => isPasswordModalOpen?.value),
          sameAs: (value) => value === formPassword?.value?.newPassword,
        },
      },
    }

    const v = useVuelidate(rules, { formPassword })

    // Computed
    const tenantId = computed(() => {
      return globalStore.getTenantId
    })

    const currentLocale = computed(() => {
      return locale.value || localStorage?.coverAdminLocale || "en-US"
    })

    const dictionary = readonly({
      "en-US": "English",
    })

    const options = computed(() => {
      const availableLocales = globalStore.getTenantSettings?.availableLocales ?? ["en-US"]
      return Object.entries(dictionary)
        .filter(([key, value]) => availableLocales.includes(key))
        .map(([key, value]) => {
          return {
            name: value,
            value: key,
          }
        })
    })
    const accountInformation = computed(() => {
      return [
        { key: "fullName", label: t("setting.fullName"), value: me?.value?.associatedUser?.name },
        { key: "email", label: t("setting.email"), value: me?.value?.username },
        { key: "tenantId", label: t("setting.tenantId"), value: tenantId.value },
      ]
    })

    // Update Modal
    const decideUpdateModal = (item) => {
      updateEntityObj.value = { ...me?.value?.associatedUser }
      openUpdateModal()
    }

    const openUpdateModal = () => {
      isEntityModalOpen.value = true
    }

    const handleUpdateEntity = async () => {
      const type = me?.value?.associatedUser?.__typename
      // different composable based on entity type
      const apiCall =
        type === "organization"
          ? // ? apiUpdateOrganization
            // : type === "internal"
            // ? apiUpdateInternal
            // : type === "object"
            // ? apiUpdateObject
            null
          : null
      const variables = {
        id: me?.value?.associatedUser?.id,
        input: {
          englishFirstName: updateEntityObj?.value?.englishFirstName,
          englishLastName: updateEntityObj?.value?.englishLastName,
        },
      }
      const { error } = await apiCall({
        variables,
        fetcher,
      })
      if (error) {
        handleErrorForUser({ error, $t: t })
        return
      } else {
        globalStore.addToastMessage({
          type: "success",
          content: {
            type: "message",
            text: t("setting.updateNameSuccessNote"),
          },
        })
      }
      closeModalAndClearForms()
      getMe()
    }

    const closeModalAndClearForms = () => {
      isEntityModalOpen.value = false
      updateEntityObj.value = {}
    }

    // Change password
    const openPasswordModal = () => {
      isPasswordModalOpen.value = true
    }

    const closeAndClearModalPassord = () => {
      isPasswordModalOpen.value = false
      v.value.$reset()
      formPassword.value = {}
    }

    const onFormValueChanged = (field) => {
      // eslint-disable-next-line no-unused-expressions
      v.value?.formPassword?.[field]?.$touch()
    }

    const handleUpdatePassword = async () => {
      v.value.$touch()
      if (v.value.$invalid) return
      // const variables = {
      //   input: {
      //     currentPassword: formPassword?.value?.currentPassword,
      //     newPassword: formPassword?.value?.newPassword,
      //   },
      // }
      // const { error } = await apiChangePassword({
      //   variables,
      //   fetcher,
      // })
      const error = null
      if (error) {
        handleErrorForUser({ error, $t: t })
        return
      } else {
        this.globalStore.addToastMessage({
          type: "success",
          content: {
            type: "message",
            text: t("setting.updatePasswordSuccessNote"),
          },
        })
      }
      closeAndClearModalPassord()
    }

    // Change language
    function openLangModal() {
      selectedLocale.value = currentLocale.value
      isLangModalOpen.value = true
    }

    function closeLangModal() {
      isLangModalOpen.value = false
    }

    function handleUpdateLocale() {
      this.globalStore.setLocale(selectedLocale.value)
      nextTick(() => {
        closeLangModal()
      })
    }

    return {
      v,
      isEntityModalOpen,
      isPasswordModalOpen,
      selectedLocale,
      isLangModalOpen,
      updateEntityObj,
      formPassword,
      isLoading,
      me,

      // computed
      currentLocale,
      dictionary,
      options,
      accountInformation,

      // update modal
      decideUpdateModal,
      closeModalAndClearForms,
      handleUpdateEntity,

      // change password
      openPasswordModal,
      handleUpdatePassword,
      onFormValueChanged,
      closeAndClearModalPassord,

      // change language
      openLangModal,
      closeLangModal,
      handleUpdateLocale,
    }
  },
}
</script>

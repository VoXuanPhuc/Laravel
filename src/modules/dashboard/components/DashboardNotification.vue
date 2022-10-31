<template>
  <!-- Title -->
  <EcFlex class="items-center">
    <EcButton variant="transparent">
      <EcIcon icon="NotiCollapse" width="48" height="48" />
    </EcButton>
    <EcLabel class="text-xl font-bold ml-2">Notifications</EcLabel>
  </EcFlex>

  <!-- List notifications -->
  <EcBox class="ml-2">
    <!-- Pinned -->
    <EcFlex class="rounded-3xl border border-c3-300 p-4 mt-8">
      <EcBox>
        <EcIcon icon="NotiPinned" width="64" height="64" />
      </EcBox>
      <EcBox class="ml-3">
        <EcLabel class="font-semibold">Pinned Notification</EcLabel>
        <EcText class="text-c3-100"> Lorem ipsum dolor sit amet, consectetur adipiscing elit.... </EcText>
        <EcText class="mt-3 text-c3-100">Now</EcText>
      </EcBox>
    </EcFlex>

    <!-- End notification -->

    <!-- 2nd -->
    <EcFlex class="rounded-3xl border border-c3-50 p-4 mt-6">
      <EcBox>
        <EcIcon icon="NotiStar" width="64" height="64" />
      </EcBox>

      <EcBox class="ml-3">
        <EcLabel class="font-semibold">Notification 2</EcLabel>
        <EcText class="text-c3-100"> Lorem ipsum dolor sit amet, consectetur adipiscing elit.... </EcText>
        <EcText class="mt-3 text-c3-100">Yesterday</EcText>
      </EcBox>
    </EcFlex>

    <!-- End notification -->

    <!-- 2nd -->
    <EcFlex class="rounded-3xl border border-c3-50 p-4 mt-6">
      <EcBox>
        <EcIcon icon="NotiCard" width="64" height="64" />
      </EcBox>
      <EcBox class="ml-3">
        <EcLabel class="font-semibold">Notification 3</EcLabel>
        <EcText class="text-c3-100"> Lorem ipsum dolor sit amet, consectetur adipiscing elit.... </EcText>
        <EcText class="mt-3 text-c3-100">7 days ago</EcText>
      </EcBox>
    </EcFlex>

    <!-- End notification -->

    <!-- 2nd -->
    <EcFlex class="rounded-3xl border border-c3-50 p-4 mt-6">
      <EcBox>
        <EcIcon icon="NotiMessage" width="64" height="64" />
      </EcBox>

      <EcBox class="ml-3">
        <EcLabel class="font-semibold">Unread Notifications</EcLabel>
        <EcText> You have 3 unread notifications </EcText>
        <EcText class="mt-3">16 days ago</EcText>
      </EcBox>
    </EcFlex>

    <!-- End notification -->
  </EcBox>
</template>

<script>
export default {
  name: "DashboardNotification",
}
</script>

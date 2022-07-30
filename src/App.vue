<template>
  <div>
    <router-view />
    <!-- Layers where we teleport modals, sidebar, ... to -->
    <div id="layer1"></div>
    <div id="layer2"></div>
    <div id="layer3"></div>
    <div id="layer4"></div>
    <div id="layer5"></div>
    <RMessage />
  </div>
</template>

<script>
export default {
  name: "App",
}
</script>

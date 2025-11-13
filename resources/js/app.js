<template>
  <RouterView/>




<loginModal></loginModal>
</template>

<script setup>

import { useUserStore } from '@/stores/userStore'
const store = useUserStore()
store.initUser()

</script>


<style>


</style>
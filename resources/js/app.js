<template>
  <RouterView/>




<loginModal></loginModal>
</template>

<script setup>
import { ref, onMounted, watch} from 'vue';
import { useOnline, useSwipe} from "@vueuse/core";
import gsap from 'gsap';
import { useMyStore } from '@/stores/animationStore';

// Local refs
const welcomeText = ref(null);
const isOnline = useOnline(); 
const myStore = useMyStore();
const sideBarRef = ref(null);

onBeforeMount(()=>{
    if(getFromLocalStore('edukita_schools')){
      myStore.schools.value = getFromLocalStore('edukita_schools');
    }else{
      myStore.fetchData("/schools", myStore.schools)
    }
  });
    watch(
      isOnline,
      async (newOnlineStatus) => {
        if (newOnlineStatus) {
          await new Promise((resolve) => setTimeout(resolve, 3000));
          if (myStore.isLoggedIn == false) {
             silentLogin();
          }
        }
      },
      { immediate: true }
    );

const silentLogin = async () => {
  const storeArray = myStore.getFromLocalStore("edukitaAuthData");
//   credential = storeArray.find((obj)=>obj.credential);
 // if (!credential) {
 //   return;
//  }
  if(storeArray.find((obj)=>obj.credential)){
    await myStore.login(credential);
  pushToLocalStore("edukitaAuthData", authData, 3600000);
  myStore.isLoggedIn = true;
    
  }
// await myStore.login(credential);
//  pushToLocalStore("edukitaAuthData", authData, 3600000);
//  myStore.isLoggedIn = true;
}



</script>


<style>


</style>
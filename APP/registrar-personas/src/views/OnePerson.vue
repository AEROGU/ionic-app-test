<template>
  <ion-page>
    <ion-header :translucent="true">
      <ion-toolbar>
        <ion-buttons slot="start">
          <ion-menu-button color="primary"></ion-menu-button>
        </ion-buttons>
        <ion-title>Persona {{ $route.params.id }}</ion-title>
      </ion-toolbar>
    </ion-header>
    
    <ion-content :fullscreen="true">
      <ion-header collapse="condense">
        <ion-toolbar>
          <ion-title size="large">Persona {{ $route.params.id }}</ion-title>
        </ion-toolbar>
      </ion-header>
    
      <div id="container">

        <ion-button color="tertiary" class="ion-activatable ripple-parent" @click="loadPerson">
          <RefreshIcon class="fill-slate-100 text-2xl"></RefreshIcon>
        </ion-button>

        <div v-if="!isLoadingPerson && founded">
          <user-card :person="person" :clickable="false" :showEditButtons="true"/>
        </div>

        <div v-if="!founded" class="text-2xl text-center mt-10">
          <strong>404</strong><br/><span>La persona no existe.</span>
        </div>

      </div>
    </ion-content>
  </ion-page>
</template>

<script lang="ts" setup>
import { IonButton, IonButtons, IonContent, IonHeader, IonMenuButton, IonPage, IonTitle, IonToolbar, onIonViewDidEnter } from '@ionic/vue';
import UserCard from '@/components/UserCard.vue';
import { useRouter, useRoute } from 'vue-router';
import { serverPersonUrl } from '@/ServerConfig';
import axios from 'axios';
import { ref, watchEffect, defineEmits, defineProps } from 'vue';
import RefreshIcon from 'vue-ionicons/dist/md-refresh.vue'

const emit = defineEmits(['updateRequired']);

const router = useRouter();
const route = useRoute();

let person = ref([]);

let isLoadingPerson = ref(false);
let founded = ref(true);

function loadPerson() {
  isLoadingPerson.value = true;
  axios.get(serverPersonUrl + route.params.id)
  .catch((error)=>{
    founded.value = false;
  })
  .then((response) => {
    if (response) {
      person.value = response.data;
      isLoadingPerson.value = false;
      founded.value = true;
    }
    isLoadingPerson.value = false;
  });
}

onIonViewDidEnter(()=>{
  loadPerson();
});

</script>
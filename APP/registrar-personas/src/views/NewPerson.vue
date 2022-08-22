<template>
  <ion-page>
    <ion-header :translucent="true">
      <ion-toolbar>
        <ion-buttons slot="start">
          <ion-menu-button color="primary"></ion-menu-button>
        </ion-buttons>
        <ion-title>Todas las personas</ion-title>
      </ion-toolbar>
    </ion-header>
    
    <ion-content :fullscreen="true">
      <ion-header collapse="condense">
        <ion-toolbar>
          <ion-title size="large">Todoas las personas</ion-title>
        </ion-toolbar>
      </ion-header>
    
      <div id="container">

        <div class="rounded p-5 my-2 md:my-5 mx-5 md:mx-28" style="background-color: rgba(91, 33, 182, 0.3);">
            <form action="" method="post">
                <label for="name">Nombre: </label><br />
                <input type="text" name="name" id="name" placeholder="Nombre de la persona" class="p-2 mb-3 min-w-full"/>
                <br />
                <label for="phone">Teléfono: </label><br />
                <input type="text" name="phone" id="phone" placeholder="Teléfono" class="p-2 mb-3 min-w-full"/>
                <br />
                <label for="email">E-Mail: </label><br />
                <input type="text" name="email" id="email" placeholder="Nombre de la persona" class="p-2 mb-3 min-w-full"/>
                <br />
                <input v-model="latitude" type="hidden" name="latitude" id="latitude" class="p-2"/>
                <input v-model="longitude" type="hidden" name="longitude" id="longitude" class="p-2"/>

                <button type="button"
                class="rounded bg-indigo-700 text-white px-5 py-2 mt-2 mr-2 disabled:bg-slate-500"
                @click="getLocation" :disabled="isLocating">
                  Ubicar
                </button>
                Latitud: <span class="italic underline">{{ latitude }}</span>, 
                Long: <span class="italic underline">{{ longitude }}</span>

                <hr class="my-2" />

                <div class="text-right">
                  <button type="submit" class="rounded bg-indigo-700 text-white px-5 py-2 disabled:bg-slate-500"
                  :disabled="!located">
                    Crear
                  </button>
                </div>
            </form>
        </div>


      </div>
    </ion-content>
  </ion-page>
</template>


<script lang="ts" setup>
import { IonButtons, IonContent, IonHeader, IonMenuButton, IonPage, IonTitle, IonToolbar } from '@ionic/vue';
import { Geolocation } from '@capacitor/geolocation';
import { ref } from 'vue';

let latitude = ref("");
let longitude = ref("");

let isLocating = ref(false);
let located = ref(false);

function getLocation() {
  

  alert("getLocation");
}

// import { Geolocation } from '@capacitor/geolocation';
// // await Geolocation.requestPermissions()
// // let havePermission = await Geolocation.checkPermissions();
// const coordinates = await Geolocation.getCurrentPosition();
// alert(coordinates.coords.latitude);
// alert(coordinates.coords.longitude);

</script>

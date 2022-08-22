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

        <div class="bg-violet-800 text-white rounded p-3 my-5 mx-5 md:mx-28">
            <p><strong>Nombre: </strong><i>Hola que hace</i></p>
            <p><strong>Teléfono: </strong><i>7555590631</i></p>
            <p><strong>E-Mail: </strong><i>holakehace@superempresa.com</i></p>
            <p><strong>Latitud: </strong><i>1295.0654</i><strong> Longitud: </strong><i>1234.5678</i></p>
        </div>


      </div>
    </ion-content>
  </ion-page>
</template>

<script lang="ts" setup>
import { IonButtons, IonContent, IonHeader, IonMenuButton, IonPage, IonTitle, IonToolbar } from '@ionic/vue';

// import { Geolocation } from '@capacitor/geolocation';
// // await Geolocation.requestPermissions()
// // let havePermission = await Geolocation.checkPermissions();
// const coordinates = await Geolocation.getCurrentPosition();
// alert(coordinates.coords.latitude);
// alert(coordinates.coords.longitude);

</script>
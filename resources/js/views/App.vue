<template>
  <div>
    <h1>sss</h1>

    <p>
      <router-link :to="{ href: '/welcome' }">Inicio</router-link>|
      <router-link :to="{ name: 'prueba' }">Prueba</router-link>|
      <router-link :to="{ name: 'persona' }">Persona</router-link>
    </p>

    <div style="padding:0 20px 0 20px ">
      <router-view></router-view>
    </div>
  </div>
</template>
<script>
export default {
  created() {
    console.log("process.env.APP_NAME", process.env.APP_NAME, process.env);
  }
};
</script>
<template>
  <div>
    <h1>SISTEMA INTEGRADO</h1>

    <p>
      <router-link :to="{ name: '' }">Inicio</router-link> |
      <router-link :to="{ name: '' }">Prueba</router-link> |
      <router-link :to="{ name: 'persona' }">Persona</router-link> 
    </p>

    <div class="container">
      <router-view></router-view>
    </div>
  </div>
</template>
<script>
export default {};
</script>
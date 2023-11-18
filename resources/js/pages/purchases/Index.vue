<template>
  <purchases-list />
</template>

<script>
import PurchasesList from './components/PurchasesList.vue'

export default {
  components: { PurchasesList },
  middleware: 'auth'
}
</script>

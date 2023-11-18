<template>
  <sales-list />
</template>

<script>
import SalesList from './components/SalesList.vue'

export default {
  components: { SalesList },
  middleware: 'auth'
}
</script>

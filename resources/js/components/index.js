import Vue from 'vue'
import Card from './Card.vue'
import Child from './Child.vue'
import Button from './Button.vue'
import Checkbox from './Checkbox.vue'

// Components that are registered globally.
[
  Card,
  Child,
  Button,
  Checkbox
].forEach(Component => {
  Vue.component(Component.name, Component)
})

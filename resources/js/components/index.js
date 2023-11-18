import Vue from 'vue'
import Card from './Card.vue'
import Child from './Child.vue'
import Button from './Button.vue'
import Checkbox from './Checkbox.vue'
import FormAlert from './FormAlert.vue'
import UserLookup from './UserLookup.vue'

// Components that are registered globally.
[
  Card,
  Child,
  Button,
  Checkbox,
  FormAlert,
  UserLookup
].forEach(Component => {
  Vue.component(Component.name, Component)
})

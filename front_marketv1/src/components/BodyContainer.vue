<template lang="jade">
div.body-container
  div.robot-bg(slot="robotBg")
  pub-task
</template>

<script>
import Pub from './Pub.vue'
export default {
  data () {
    return {
    }
  },
  components: {
    'PubTask': Pub
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
.body-container {
  margin: 0 10px;
  padding: 0 10px;
}
div.robot-bg {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1;
  width: 100%;
  height: 100%;
}
</style>

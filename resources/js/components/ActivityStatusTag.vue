<template>
  <div>
    <el-tag :size="size" :type="type">{{ status }}</el-tag>
  </div>
</template>

<script type="text/babel">
export default {
  props: {
    status: {
      type: String,
      required: true
    },
    size: {
      type: String,
      default: 'medium'
    }
  },
  computed: {
    type() {
      let type = 'default'
      switch (this.status) {
        case 'À démarrer':
          type = ''
          break;
        case 'À arbitrer':
          type = 'warning'
          break;
        case 'En cours':
          type = 'success'
          break;
        case 'Supprimé':
          type = 'danger'
          break;
        case 'Gelé':
        case 'Abandonné':
          type = 'info'
          break;
        default:
          type = '';
      }

      return type
    }
  }
};
</script>

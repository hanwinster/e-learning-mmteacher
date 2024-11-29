<template>
  <!-- Icon Divider-->
  <div class="divider-custom">
    <div class="divider-custom-line" v-bind:class="bgColor"></div>
    <div class="divider-custom-icon">
      <span v-bind:class="color">
        <i class="fas fa-graduation-cap"></i>
      </span>
    </div>
    <div class="divider-custom-line" v-bind:class="bgColor"></div>
  </div>
</template>

<script>
export default {
  data() {
    return {
          //color: this.color.value ? this.color.value : this.color.default
    }
  },
  props: {
    color: {
      default: 'text-black',
      type: String,
      required: false,
      value: null
    },
    bgColor: {
      default: 'bg-black',
      type: String,
      required: false,
      value: null
    }
  },
  mounted() {
    console.log(this);
    this.test();
  },
//   computed() {

//   },
  methods: {
    test: function () {
        //console.log('divider is loaded');
    },
  },
}
</script>

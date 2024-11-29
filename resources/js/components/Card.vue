<template>
  <div v-bind:class="grid">
    <div class="card card-outline" v-bind:class="cardBorderColor">
      <div class="card-header">
        <h3 class="card-title">{{ title }}&nbsp;
          <span  v-if="cardTooltip" class="tooltip-info text-info" data-toggle="tooltip"
             data-placement="top" title="" v-bind:title="cardTooltip">
              <i class="fas fa-info-circle"></i>
          </span>
        </h3>
 
        <div class="card-tools">
          <button
            type="button"
            class="btn btn-tool"
            data-card-widget="collapse"
          >
            <i class="fas fa-minus"></i>
          </button>
        </div>
        <!-- /.card-tools -->
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        {{countData}}
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      //color: this.color.value ? this.color.value : this.color.default
    };
  },
  props: {
    title: {
      default: "",
      type: String,
      required: false,
      value: null,
    },
    countData: {
      default: "",
      type: String,
      required: false,
      value: null,
    },
    grid: {
      default: "col-3",
      type: String,
      required: false,
      value: null,
    },
    cardBorderColor: {
      default: "card-info",
      type: String,
      required: false,
      value: null,
    },
    cardTooltip: {
      default: "",
      type: String,
      required: false,
      value: null,
    }
  },
  mounted() { 
      //console.log(this.card)
  },
  //   computed() {

  //   },
  methods: {
    test: function () {
      //console.log('card is loaded');
    },
  },
};
</script>

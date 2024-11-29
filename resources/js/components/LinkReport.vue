<template>
<div class="link-report-component">

<!-- Fade In Modal -->
<div class="modal fade" id="modal-link-report" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content container">
        <div class="component-resource">
        <div class="block block-themed block-transparent remove-margin-b">
            <div class="block-header bg-primary-dark">
                <h3 class="block-title mt-3">Report</h3>
            </div>
            <div class="block-content">
                <div v-if="!logged_in">
                    Please <a href="/login">login</a> to report.
                </div>
                <div v-else>
                    <div class="mt-3 alert alert-danger" v-if="errors.length > 0">
                        <ul>
                            <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
                        </ul>
                    </div>

                    <div class="row">
                    <div class="col-md-12">
                    <div class="form-group">
                        <label for="report_type">Report Type</label>
                        <select autofocus v-model="report_type" class="form-control" style="width: 30%" name="report_type" id="report_type">
                            <option disabled>-Choose-</option>
                            <option v-for="type in report_types" v-bind:value="type.id" :key="type.id">
                                {{ type.title }}
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <textarea name="comment" value="" placeholder="Enter Comment" rows="3" class="form-control" v-model="comment"></textarea>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-default" type="button" @click="submitReport()">Submit</button>

                    </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Close
            </button>
        </div>
    </div>
     </div>
    </div>
</div>
<!-- END Fade In Modal -->
</div>
</template>
<script>
export default {
    props: ['resource_id', 'logged_in'],

    data: function () {
        return {
            report_type: '',
            report_types: [],
            comment: '',
            errors: []
        }
    },

    mounted() {
        this.errors = [];
        this.populate()
    },

    methods: {
        populate() {
            let pageUrl = '/api/report-type';

            axios.get(pageUrl).then(response => {
                this.report_types = response.data;
                console.log(this.report_types)
                console.log(response.data[0].title)
            });
        },

        submitReport() {
            let pageUrl = '/api/member/resource/'+ this.resource_id + '/report';

            axios.post(pageUrl,
            {
                report_type: this.report_type,
                comment: this.comment,
                resource_id: this.resource_id,
            }
            ).then(response => {
                this.reset();

                $("#modal-link-report").modal("hide");
            })
            .catch(error => {
                this.errors = [];
                if (error.response.data.errors.report_type) {
                    this.errors.push(error.response.data.errors.report_type[0]);
                }

                if (error.response.data.errors.comment) {
                    this.errors.push(error.response.data.errors.comment[0]);
                }
            });
        },

        reset () {
            this.errors = [];
            this.report_type = '';
            this.comment = '';
        }
    }
}
</script>

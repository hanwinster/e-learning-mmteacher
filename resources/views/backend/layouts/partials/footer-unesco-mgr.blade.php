<!-- jQuery -->
<script src="{{ url('assets/backend/adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ url('assets/backend/adminlte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>

<!-- Bootstrap 4 -->
<script src="{{ url('assets/backend/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
@php 
    $isRoot = ( $_SERVER['REQUEST_URI'] === '/en/dashboard' || $_SERVER['REQUEST_URI'] === '/my-MM/dashboard') ? true : false;
    if( $isRoot && auth()->user()->isUnescoManager() ) { @endphp
        <script src="{{ url('assets/backend/adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ url('assets/backend/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ url('assets/backend/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ url('assets/backend/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
        <script src="{{ url('assets/backend/adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ url('assets/backend/adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
        <script src="{{ url('assets/backend/adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
        <script src="{{ url('assets/backend/adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
        <script src="{{ url('assets/backend/adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
@php    
    }
@endphp
<!-- ChartJS -->
<script src="{{ url('assets/backend/adminlte/plugins/chart.js/Chart.min.js') }}"></script>

<script src="{{ url('assets/backend/adminlte/plugins/moment/moment.min.js') }}"></script>
<script src="{{ url('assets/backend/adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ url('assets/backend/adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

<!-- overlayScrollbars -->
<script src="{{ url('assets/backend/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ url('assets/backend/adminlte/adminlte.min.js') }}"></script>

<script src="{{ asset('js/app.js') }}"></script>
<script>
    var currentLang = $('html').attr('lang');
    function sendNotification(passedKey) { 
        //console.log('key is ', passedKey); 
        let passedValue = $('#data-for-dialog-'+passedKey).val();
       // console.log('value  is ', passedValue); 
        let temp = passedValue.split('-'); //console.log(temp);
        let courseTitle = temp[2]; 
        let userIdAndLearnerId = temp[0]+'_'+temp[1];
        $('#user-id').val(userIdAndLearnerId); //this.value);
        if(currentLang == 'en') {
                $('#noti-subject').val("Notification to complete the course ( "+courseTitle+" )"); 
                $('#noti-message').val("Please be informed that the time allowed to finish the course ( "+courseTitle+" ) is over! Could you please login and complete the course as soon as possible?");
        } else {
                $('#noti-subject').val(courseTitle+" သင်တန်းအားပြီးဆုံးအောင် လုပ်ဆောင်ရန် သတိပေးခြင်း"); 
                $('#noti-message').val("သင်ယူထားသော ( "+courseTitle+" ) သင်တန်းအတွက် ပေးထားသောအချိန်မှာ ကျော်လွန်သွားပြီ  ဖြစ်ပါသည်။ ကျေးဇူးပြု၍ စနစ်သို့ဝင်ရောက်ပြီး သင်တန်းပြီးဆုံးအောင် လုပ်ဆောင်ပေးပါ။");
        }
    }
    function removeUserFromCourse(learnerIdToDelete) {
        //console.log('learnerIdToDelete is ', learnerIdToDelete); 
        $('#remove-user-id').val(learnerIdToDelete);
    }
    $(document).ready(function() {
        //Date range as a button
        $('#gender-select-umgr').daterangepicker(
        {
            ranges   : {
            'Today'       : [moment(), moment()],
            'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month'  : [moment().startOf('month'), moment().endOf('month')],
            'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'Last Year'  :  [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
            },
            startDate: moment().subtract(29, 'days'),
            endDate  : moment()
        }, function (start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
            }
        );
        $('#db-course-table-umgr').DataTable({
            "responsive": true, 
            "lengthChange": false, 
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#db-course-table-umgr_wrapper .col-md-6:eq(0)');
        $('#edc-table-umgr').DataTable({
            "responsive": true, 
            "lengthChange": false, 
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#edc-table-umgr_wrapper .col-md-6:eq(0)');
        $('#notify-table-umgr').DataTable({
            "responsive": true, 
            "lengthChange": false, 
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#notify-table-umgr_wrapper .col-md-6:eq(0)');
        $('#visitors-table-umgr').DataTable({
            "responsive": true, 
            "lengthChange": false, 
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#visitors-table-umgr_wrapper .col-md-6:eq(0)');

        
        //$('.sendNotiMail').click(function(e) { console.log("clicked ", this.value);
            // let temp = this.value.split('_'); //console.log(temp);
            // let courseTitle = temp[2]; 
            // let userIdAndLearnerId = temp[0]+'_'+temp[1];
            // $('#user-id').val(userIdAndLearnerId); //this.value);
            // if(currentLang == 'en') {
            //     $('#noti-subject').val("Notification to complete the course ( "+courseTitle+" )"); 
            //     $('#noti-message').val("Please be informed that the time allowed to finish the course ( "+courseTitle+" ) is over! Could you please login and complete the course as soon as possilbe.");
            // } else {
            //     $('#noti-subject').val(courseTitle+" သင်တန်းအားပြီးဆုံးအောင် လုပ်ဆောင်ရန် သတိပေးခြင်း"); 
            //     $('#noti-message').val("သင်ယူထားသော ( "+courseTitle+" ) သင်တန်းအတွက် ပေးထားသောအချိန်မှာ ကျော်လွန်သွားပြီ  ဖြစ်ပါသည်။ ကျေးဇူးပြု၍ စနစ်သို့ဝင်ရောက်ပြီး သင်တန်းပြီးဆုံးအောင် လုပ်ဆောင်ပေးပါ။");
            // }
            
       // });
        $('.removeUserFromCourse').click(function() { //console.log("clicked ", this.value);
            $('#remove-user-id').val(this.value);
        });
        $('.tooltip-info').tooltip();
    });
    
    function convertDate(dateString) {
        var arr = dateString.split('/');
        return arr[2] + '-' + arr[1] + '-' + arr[0];
    }
    function addData(chartInstance, chart, label, data) {
        chartInstance.config.data.labels= label;
        chartInstance.config.data.datasets[0]['data'] = data;
        chartInstance.update();
        //console.log('after update ', lineChart);
    }
    function removeData(chartInstance, chart) {
        chartInstance.config.data.labels =[];
        chartInstance.config.data.datasets[0]['data'] = [];
        chartInstance.update();
    }

    function removeInvalidInputClasses(idOrClassName, classToRemove) {
        if( $(idOrClassName).hasClass(classToRemove) ) {
            $(idOrClassName).removeClass(classToRemove);
        }
    }
    
    function validateDateInputs() {
        let valid = true;
        if( $('#signup-start-date-btn-mgr').val() == null || $('#signup-start-date-btn-mgr').val() == '') {
            $('#signup-start-date-btn-mgr').addClass('is-invalid');
            valid = false;
        }
        if( $('#signup-end-date-btn-mgr').val() == null || $('#signup-end-date-btn-mgr').val() == '') {
            $('#signup-end-date-btn-mgr').addClass('is-invalid');
            valid = false;
        }
        return valid; 
    }
    function selectContents(el) {
        var body = document.body, range, sel;
        if (document.createRange && window.getSelection) {
            range = document.createRange();
            sel = window.getSelection();
            sel.removeAllRanges();
            try {
                range.selectNodeContents(el);
                sel.addRange(range);
            } catch (e) {
                range.selectNode(el);
                sel.addRange(range);
            }
        } else if (body.createTextRange) {
            range = body.createTextRange();
            range.moveToElementText(el);
            range.select();
        }
    }
    
    var stDate = {!!json_encode($startDate, JSON_HEX_TAG) !!};
    var eDate = {!!json_encode($endDate, JSON_HEX_TAG) !!};
    
        $('#signup-start-date-mgr').datetimepicker({
            format: 'DD/MM/YYYY',
            minDate: new Date('2018-10-28'), //new Date('2020-01-01')
            maxDate: new Date(),
            date: new Date(stDate)
        });
        $('#signup-end-date-mgr').datetimepicker({
            format: 'DD/MM/YYYY',
            minDate: new Date('2018-10-28'),
            maxDate: new Date(), //eDate
            date: new Date(eDate)
        });
        $("#signup-start-date-mgr").on("change.datetimepicker", function(e) {
            $('#signup-end-date-btn-mgr').val('');
            if( $('#signup-start-date-btn-mgr').val()) {
                var stDate = convertDate($("#signup-start-date-btn-mgr").val());
                $('#signup-end-date-mgr').data("datetimepicker").minDate(new Date(stDate));
                removeInvalidInputClasses('#signup-start-date-btn-mgr', 'is-invalid');
                $("input[name=startDate]").val( convertDate($('#signup-start-date-btn-mgr').val()));
                if( $('#signup-end-date-btn-mgr').val()) {
                    removeInvalidInputClasses('#downloadSignupsUnescoMgr', 'disabled');
                }
            }  else {
                $('#signup-start-date-btn-mgr').addClass('is-invalid');
                $('#downloadSignupsUnescoMgr').addClass('disabled');
            }
        });
        $("#signup-end-date-mgr").on("change.datetimepicker", function(e) {
            if( $('#signup-end-date-btn-mgr').val()) {
                removeInvalidInputClasses('#signup-end-date-btn-mgr', 'is-invalid');
                $("input[name=endDate]").val(convertDate($('#signup-end-date-btn-mgr').val()));
                if( $('#signup-start-date-btn-mgr').val()) {
                    removeInvalidInputClasses('#downloadSignupsUnescoMgr', 'disabled');
                }
            } else {
                $('#signup-end-date-btn-mgr').addClass('is-invalid');
                $('#downloadSignupsUnescoMgr').addClass('disabled');
            }
        });
        
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var domain = "{{ env('APP_URL') }}"; 
        
        $('#searchSignupDataMgr').on("click", function(e) { 
            e.preventDefault();
            if(!validateDateInputs()) return false;
            
            let url = domain + "/api/getTotalSignups/" + convertDate($("#signup-start-date-btn-mgr").val()) + 
                        '/' + convertDate($("#signup-end-date-btn-mgr").val());
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json',
                data: null,
                beforeSend: function() {
                    $('.d-spinner').removeClass('d-none');
                    $('.d-spinner').css('margin-top','-200px');
                    $('.chart').addClass('v-hidden');
                },
                success: function(response) { //console.log(response)
                    $('.d-spinner').addClass('d-none');
                    $('.d-spinner').css('margin-top','0px');
                    $('.chart').removeClass('v-hidden');       
                    removeData(lineChart, chartData);
                    addData(lineChart, chartData,response.data.label,response.data.data);
                },
                error: function (response) {                
                    if(response.status === 500) {
                        $('.chart').html(
                            '<div class="row text-center mt-5"><div class="col"><h3>500 Server Error! Sorry, We Will Fix Error Soon,'+
                            ' Please Try Again Later!</h3></div></div>'
                        );
                    } else {
                        console.log("error response ", response)
                        $('.chart').html(
                            '<div class="row text-center mt-5"><div class="col"><h3>Please Check Your Connection And Try Later!</h3>' + 
                            '</div></div>'
                        );
                    }
                }
            });
        });
    
        function removeDonutData() {
            donutData.labels = [];
            donutData.datasets = [{
                                    data: [],
                                    backgroundColor: ['#f56954', '#3c8dbc', '#333333'],
                                }];
        }

        function addDonutData(labels, datasets) {
            donutData.labels = labels;
            donutData.datasets = [{
                                    data: datasets,
                                    backgroundColor: ['#f56954', '#3c8dbc', '#333333'],
                                }];
        }
        $('#gender-select-umgr').on('apply.daterangepicker', function(e, picker) { // on selected
            e.preventDefault();
            let startDate = picker.startDate.format('YYYY-MM-DD'); // $('#gender-select-umgr').data('daterangepicker').startDate._d;
            let endDate = picker.endDate.format('YYYY-MM-DD'); //$('#gender-select-umgr').data('daterangepicker').endDate._d;
            //console.log(startDate);
            //console.log(picker.startDate.format('DD-MM-YYYY'));
            let url = domain + "/api/get-gender-by-date/" + startDate + '/' + endDate;
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json', 
                data: null,
                beforeSend: function() {
                    $('.d-spinner-gender').removeClass('d-none');
                    $('.d-spinner-gender').css('margin-top','-200px');
                    $('#gender-donut-chart').addClass('v-hidden');
                },
                success: function(response) { //console.log(response);
                    $('.d-spinner-gender').addClass('d-none');
                    $('.d-spinner-gender').css('margin-top','0px');
                    $('#gender-donut-chart').removeClass('v-hidden');       
                    removeDonutData();
                    addDonutData(response.donutChartLabel, response.donutChartData);
                },
                error: function (response) {                
                    if(response.status === 500) {
                        $('#gender-donut-chart').html(
                            '<div class="row text-center mt-5"><div class="col"><h3>500 Server Error! Sorry, We Will Fix Error Soon,'+
                            ' Please Try Again Later!</h3></div></div>'
                        );
                    } else {
                        //console.log("error response ", response)
                        $('#gender-donut-chart').html(
                            '<div class="row text-center mt-5"><div class="col"><h3>Please Check Your Connection And Try Later!</h3>' + 
                            '</div></div>'
                        );
                    }
                }
            });
        });

        // $('#gender-select-umgr').on("click", function(e) { //just on click
            
        // });

    var donutChartData = {!!json_encode($donutChartData, JSON_HEX_TAG) !!};
    var donutChartLabel = {!!json_encode($donutChartLabel, JSON_HEX_TAG) !!};

    var donutChartCanvas = $('#gender-donut-chart').get(0).getContext('2d')
    var donutData = {
        labels: donutChartLabel,
        datasets: [{
            data: donutChartData,
            backgroundColor: ['#f56954', '#3c8dbc', '#333333'],
        }]
    }
    var donutOptions = {
        maintainAspectRatio: false,
        responsive: true,
    }

    // Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    new Chart(donutChartCanvas, {
        type: 'doughnut',
        data: donutData,
        options: donutOptions
    });

    //-------------
    //- LINE CHART -
    //--------------
    
    var labelData = {!!json_encode($lineGraphLabel, JSON_HEX_TAG) !!};
    var lineGraphData = {!!json_encode($lineGraphData, JSON_HEX_TAG) !!};
    
    var chartData = {
        labels: labelData,
        datasets: [{
            label: 'Signups  ',
            backgroundColor: 'rgba(60,141,188,0.9)',
            borderColor: 'rgba(60,141,188,0.8)',
            pointRadius: 3,
            pointColor: '#3b8bba',
            pointStrokeColor: 'rgba(60,141,188,1)',
            pointHighlightFill: '#fff',
            pointHighlightStroke: 'rgba(60,141,188,1)',
            data: lineGraphData
        }, ]
    };
    var chartOptions = {
        maintainAspectRatio: false,
        responsive: true,
        legend: {
            display: false
        },
        scales: {
            xAxes: [{
                gridLines: {
                    display: false,
                }
            }],
            yAxes: [{
                gridLines: {
                    display: true,
                }
            }]
        }
    };
    var lineChartCanvas = $('#signup-chart').get(0).getContext('2d');
    var lineChartOptions = $.extend(true, {}, chartOptions);
    var lineChartData = $.extend(true, {}, chartData)
    lineChartData.datasets[0].fill = false;
    //lineChartData.datasets[1].fill = false;
    lineChartOptions.datasetFill = false

    var lineChart = new Chart(lineChartCanvas, {
        type: 'line',
        data: lineChartData,
        options: lineChartOptions
    });
    //console.log('on load', lineChart);

</script>
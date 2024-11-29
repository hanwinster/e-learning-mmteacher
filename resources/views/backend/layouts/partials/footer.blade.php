<!-- jQuery -->
<script src="{{ url('assets/backend/adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ url('assets/backend/adminlte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>

<!-- Bootstrap 4 -->
<script src="{{ url('assets/backend/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ url('assets/backend/adminlte/plugins/chart.js/Chart.min.js') }}"></script>
<!-- Select 2 -->
<script src="{{ url('assets/backend/adminlte/plugins/select2/js/select2.full.min.js') }}"></script>

<script src="{{ url('assets/backend/adminlte/plugins/moment/moment.min.js') }}"></script>
<script src="{{ url('assets/backend/adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ url('assets/backend/adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

<!-- overlayScrollbars -->
<script src="{{ url('assets/backend/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ url('assets/backend/adminlte/adminlte.min.js') }}"></script>

<script src="{{ asset('js/app.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
    $.widget.bridge('uibutton', $.ui.button); // Resolve conflict in jQuery UI tooltip with Bootstrap tooltip
    var alphabets = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
    var currentLang = $('html').attr('lang');
    
    function sendNotification(passedKey) { 
        let passedValue = $('#data-for-tdialog-'+passedKey).val();
        let temp = passedValue.split('-'); console.log(temp);
        let courseTitle = temp[2]; 
        let userIdAndLearnerId = temp[0]+'_'+temp[1];
        $('#user-id-t').val(userIdAndLearnerId); //this.value);
        if(currentLang == 'en') {
                $('#noti-subject-t').val("Notification to complete the course ( "+courseTitle+" )"); 
                $('#noti-message-t').val("Please be informed that the time allowed to finish the course ( "+courseTitle+" ) is over! Could you please login and complete the course as soon as possible?");
        } else {
                $('#noti-subject-t').val(courseTitle+" သင်တန်းအားပြီးဆုံးအောင် လုပ်ဆောင်ရန် သတိပေးခြင်း"); 
                $('#noti-message-t').val("သင်ယူထားသော ( "+courseTitle+" ) သင်တန်းအတွက် ပေးထားသောအချိန်မှာ ကျော်လွန်သွားပြီ  ဖြစ်ပါသည်။ ကျေးဇူးပြု၍ စနစ်သို့ဝင်ရောက်ပြီး သင်တန်းပြီးဆုံးအောင် လုပ်ဆောင်ပေးပါ။");
        }
    }

    function removeUserFromCourse(learnerIdToDelete) {
        //console.log('learnerIdToDelete is ', learnerIdToDelete); 
        $('#remove-user-id').val(learnerIdToDelete);
    }
    $(document).ready(function() {
      //Initialize Select2 Elements
      $('.select2').select2();

      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });
      var currentLang = $('html').attr('lang');
      $('.sendNotiMail').click(function() { console.log("clicked ", this.value);
        let temp = this.value.split('_'); //console.log(currentLang);
        let courseTitle = temp[2]; 
        let userIdAndLearnerId = temp[0]+'_'+temp[1];
        $('#user-id-t').val(userIdAndLearnerId); //this.value);
        if(currentLang == 'en') {
              $('#noti-subject-t').val("Notification to complete the course ( "+courseTitle+" )"); 
              $('#noti-message-t').val("Please be informed that the time allowed to finish the course ( "+courseTitle+" ) is over! Could you please login and complete the course as soon as possilbe.");
        } else {
              $('#noti-subject-t').val(courseTitle+" သင်တန်းအားပြီးဆုံးအောင် လုပ်ဆောင်ရန် သတိပေးခြင်း"); 
              $('#noti-message-t').val("သင်ယူထားသော ( "+courseTitle+" ) သင်တန်းအတွက် ပေးထားသောအချိန်မှာ ကျော်လွန်သွားပြီ  ဖြစ်ပါသည်။ ကျေးဇူးပြု၍ စနစ်သို့ဝင်ရောက်ပြီး သင်တန်းပြီးဆုံးအောင် လုပ်ဆောင်ပေးပါ။");
        }
      });

      // $('.removeUserFromCourse').click(function() { console.log("clicked ", this.value);
      //     $('#remove-user-id').val(this.value);
      // });
    
      $(document).on('click', '.edit-modal', function() {
           // console.log('clicked');
            $('#footer_action_button').text(" Update");
            $('#footer_action_button').addClass('glyphicon-check');
            $('#footer_action_button').removeClass('glyphicon-trash');
            $('.actionBtn').addClass('btn-success');
            $('.actionBtn').removeClass('btn-danger');
            $('.actionBtn').addClass('edit');
            $('.modal-title').text($(this).data('value'));
            $('.deleteContent').hide();
            $('.form-horizontal').show();
            $('#assignment_user_id').val($(this).data('id'));
            $('#comment').val($(this).data('comment'));
            $('#myModal').modal('show');
        });
        $('.modal-footer.user-assignment').on('click', function(e) { //console.log('clicked modal footer');
              $.ajax({
                  type: 'POST',
                  url: '{{route("member.ajax-assignment-review")}}',
                  data: {
                      _token: $('input[name=_token]').val(),
                      id: $("#assignment_user_id").val(),
                      comment: $('#comment').val(),
                      score: $('#score').val()
                  },
                  success: function(data) {// console.log('success data after reveiwing ',data);
                    let id = $("#assignment_user_id").val();
                      $('.item_row' + id).replaceWith(data);
                  }
              });
        });
        $('.modal-footer.user-la-feedback').on('click', function(e) { //console.log('clicked modal footer');
              $.ajax({
                  type: 'POST',
                  url: '{{route("member.ajax-long-answer-review")}}',
                  data: {
                      _token: $('input[name=_token]').val(),
                      id: $("#answer_id").val(),
                      comment: $('#comment').val(),
                      status: $('#status').val()
                  },
                  success: function(data) {// console.log('success data after reveiwing ',data);
                    let id = $("#answer_id").val();
                      $('.item_row' + id).replaceWith(data);
                  }
              });
        });
        
        $(document).on('click', '.nav-item', function() { 
          showAlertForOrder();
        });

        showAlertForOrder();
        function showAlertForOrder() {
          var hash = window.location.hash, //get the hash from url
          cleanhash = hash.replace("#", ""); //remove the #   
          if(cleanhash == 'nav-order') {
              $("#modal-order-alert").modal({
                backdrop: 'static',
                keyboard: false,
                show: true // added property here
              });
          }
        }
        
        $(document).on('click', '.add-rearrange-answers', function(e) {
          
        });

        $(document).on('click', '.edit-assess-la-modal', function() { 
           //console.log('clicked', $(this).data('assessstatus'));
            $('#footer_action_button').text(" Update");
            $('#footer_action_button').addClass('glyphicon-check');
            $('#footer_action_button').removeClass('glyphicon-trash');
            $('.actionBtn').addClass('btn-success');
            $('.actionBtn').removeClass('btn-danger');
            $('.actionBtn').addClass('edit');
            $('.modal-title').text($(this).data('value'));
            $('.deleteContent').hide();
            $('.form-horizontal').show();
            $('#assessment_user_id').val($(this).data('id'));
            $('#assesscomment').val($(this).data('assesscomment'));
            $('#assessstatus').val($(this).data('assessstatus'));
            $('#assess-long-answer-modal').modal('show');
        });

        $('.modal-footer.user-assess-la-feedback').on('click', function(e) { //console.log('clicked modal footer');
              $.ajax({
                  type: 'POST',
                  url: '{{route("member.course.ajax-assess-long-answer-review")}}',
                  data: {
                      _token: $('input[name=_token]').val(),
                      id: $("#assessment_user_id").val(),
                      comment: $('#assesscomment').val(),
                      pass_option: $('#assessstatus').val()
                  },
                  success: function(data) { //console.log('success data after reveiwing ',data);
                    let id = $("#assessment_user_id").val();
                      $('.item_row'+ id).replaceWith(data);
                  }
              });
        });
      
      $('.removeUserFromCourse').click(function() { //console.log("clicked ", this.value);
          $('#remove-user-id').val(this.value);
      });
      
      // var HelloButton = function (context) { 
      //   var ui = $.summernote.ui;

      //   // create button
      //   var button = ui.button({
      //     contents: '<i class="fas fa-comment"></i>',
      //     tooltip: 'Add Hello',
      //     click: function () {
      //       // invoke insertText method with 'hello' on editor module.
      //       context.invoke('editor.insertText', 'Hello');
      //       //$('#st-editor').summernote('editor.insertText', 'hello'); //not working
      //     }
      //   });

      //   return button.render();   // return button as jquery object
      // }

      function initializeSummernote() {
          $('.summernote').summernote({
            fontNames: ['Roboto', 'sans-serif','Comic Sans MS','Calibri', 'Times New Roman', 'Arial', 'Helvetica','Pyidaungsu'],
            fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '24', '36', '48' , '64', '72'],
            toolbar: [
              ['style', ['style']],
              ['fontsize', ['fontsize']],
              ['font', ['bold', 'italic', 'underline', 'clear']],
              ['fontname', ['fontname'] ],
              ['fontcolor', ['fontcolor'] ],
              ['color', ['color']],
              ['para', ['ul', 'ol', 'paragraph']],
              ['height', ['height']],
              ['insert', ['picture','video']],
              ['table', ['table']],
              ['link', ['link']],
              ['view', ['codeview']],
              // ['mybutton', ['hello']]
            ],
            // buttons: {
            //     hello: HelloButton
            //   },
          // height: 585,
            focus: false,
            dialogsInBody: true,
            dialogsFade: true,
            codeviewFilter: false,
            codeviewIframeFilter: true
          });
        }
        initializeSummernote();

      $('#zoom-start-date').datetimepicker({
            format: 'DD/MM/YYYY',
            minDate: new Date(), // maxDate: new Date(), //date: new Date(stDate)
           // date: new Date()
      });

      $('#zoom-start-time').datetimepicker({
         format: 'HH:mm'
      });

      $('.tooltip-info').tooltip();
    });

    const SELECTOR_PRELOADER = '.preloader';
    const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'l',
        panelAutoHeight: true,
        panelAutoHeightMode: 'min-height',
        preloadDuration: 200,
        loginRegisterAutoHeight: true
    };

    function initialize() {
        setTimeout(() => {
            const $preloader = $(SELECTOR_PRELOADER)
            if ($preloader) {
                $preloader.css('height', 0)
                setTimeout(() => {
                $preloader.children().hide()
                }, 200)
            }
        }, Default.preloadDuration);
        checkVideoLink();
    }

    initialize();
    /* Course Form */
    function checkVideoLink() {
      if ($('#is-display-video').is(':checked')) { 
          $('.video-link-div').show();
          $('#is-display-video').val(1);
          $("input[name=video_link]").prop('required',true);
      } else { //console.log('un-checked')
          $('.video-link-div').hide();
          $('#is-display-video').val(0);
          $("input[name=video_link]").prop('required',false);
      }
    }
    
    $('#is-display-video').change(function() { 
      checkVideoLink();
    });
    /* Lecture Form */
    function checkResourceType() {
      let selectedVal = $('input[name="resource_type"]:checked').val(); //alert(selectedVal);
          
      if (selectedVal == 'none') { 
          $('#lecture-embed-video').addClass('d-none');
          $('#lecture-attach-file').addClass('d-none'); 
          $("input[name=video_link]").prop('required',false);
          $("input[name=attached_file]").prop('required',false);
      } else if (selectedVal == 'embed_video') { 
          $('#lecture-embed-video').removeClass('d-none');
          $('#lecture-attach-file').addClass('d-none');
          $("input[name=video_link]").prop('required',true);
          $("input[name=attached_file]").prop('required',false);
      } else { //console.log('un-checked')
          $('#lecture-embed-video').addClass('d-none');
          $('#lecture-attach-file').removeClass('d-none');
          $("input[name=video_link]").prop('required',false);
          $("input[name=attached_file]").prop('required',true);
      }
    }
    
    $('.resource-type').change(function() { 
      checkResourceType();
    });
    /* Assessment QA Form */
    function showHideAssessTypes(selectedType)
    {  
      let allIds = ['assess-true_false', 'assess-multiple_choice','assess-rearrange','assess-matching'];
      for(let i = 0; i < allIds.length; i++) {
        if(allIds[i] == selectedType) { //console.log('selected ', '#'+selectedType);
          $('#'+selectedType).removeClass('d-none');
          if(selectedType == 'true_false') {
            $('#tf-form').removeClass('d-none');
            $('#mc-form').addClass('d-none');
            $('#matching-form').addClass('d-none');
            $('#rf-form').addClass('d-none');
          }
        } else { console.log('not selected ', '#'+selectedType);
          $('#'+allIds[i]).addClass('d-none');
        }
      }     
    }

    $('#assessment-type').change(function() { 
      let selectedType = $('#assessment-type').val();
      showHideAssessTypes('assess-'+selectedType);
    });

    function hideFormElements(inputName)
    {
      var inputs = document.getElementsByTagName('input');
      for(var i = 0;i < inputs.length; i++) {
          if(inputs[i].style.display == 'none') {
              inputs[i].disabled = true;
          }
      }
      document.forms[0].submit();
    }

    $('.remove_image').on('click', function() {
            let yes_or_no = confirm("Are you sure to remove?");
            if (yes_or_no == true) {
              let url = $(this).data('href');
              $.get(url, function(data, status){
                // alert("Data: " + data + "\nStatus: " + status);
                if (status == 'success') {
                  $('#file_wrap').remove();
                }
              });
            }
      });
      
    let url = location.href.replace(/\/$/, "");
    if (location.hash) {
        const hash = url.split("#");
        $('#nav-tab a[href="#'+hash[1]+'"]').tab("show");
        url = location.href.replace(/\/#/, "#");
        history.replaceState(null, null, url);
        setTimeout(() => {
            $(window).scrollTop(0);
        }, 400);
    }

      $('a[data-toggle="tab"]').on("click", function() { 
            let newUrl;
            const hash = $(this).attr("href");
            if(hash == "#nav-home") {
              newUrl = url.split("#")[0];
            } else {
              newUrl = url.split("#")[0] + hash;
            }
            // newUrl += "/";
            history.replaceState(null, null, newUrl);
      });

    function toggleZoomTable() {
            var x = document.getElementById("zoom-table");
            var y = document.getElementById("zoom-entry");
            var z = document.getElementById("new-session-btn");
            if (x.style.display === "none") {
                x.style.display = "inline-block";
                y.style.display = "none";
                z.classList.remove("d-none");
            } else {
                x.style.display = "none";
                y.style.display="block";
                z.classList.add("d-none");
            }
    }
    $('#account-type_adm').change(function() {
            //console.log('account type changed!')
            if ($('#account-type_adm').val() == 1) { //teacher type
              
                $('.year-div').show();
                $('.ec-div').show();
                $('.user-type-teacher').removeClass('d-none');
                $('.user-type-all').addClass('d-none');
                
            } else {
                $('.year-div').hide();
                $('.ec-div').hide();
                $('.user-type-teacher').addClass('d-none');
                $('.user-type-all').removeClass('d-none');
            }
            if($('.affiliation-div').hasClass('d-none')) {
                
            } else {
                $('.affiliation-div').addClass('d-none');
                $('.position-div').addClass('d-none');
            }          
        });

        $('.user_types_all').change(function() {
            //console.log('user type changed!', $('.user_types_all').val());
            if ( $('.user_types_all').val() == 'journalist') {
                $('.year-div').hide();
                $('.ec-div').hide();
                $('.organization-div').addClass('d-none');
                $('.affiliation-div').removeClass('d-none');
                $('.position-div').removeClass('d-none');
            } else if( $('.user_types_all').val() == 'independent_learner') { 
                $('.year-div').hide();
                $('.ec-div').hide(); 
                $('.organization-div').removeClass('d-none');
                $('.affiliation-div').addClass('d-none');
                $('.position-div').addClass('d-none');
            } else { //edc
                $('.year-div').show(); 
                $('.ec-div').show();
                $('.organization-div').addClass('d-none');
                $('.affiliation-div').addClass('d-none');
                $('.position-div').addClass('d-none');
            }
        });
        $('.user_types_teacher').change(function() {
            if($('.user_types_teacher').val() == 'independent_teacher') {
                $('.year-div').hide();
                $('.ec-div').hide();
            } else {
                $('.year-div').show();
                $('.ec-div').show();
            }
            if($('.affiliation-div').hasClass('d-none')) {
                
            } else {
                $('.affiliation-div').addClass('d-none');
                $('.position-div').addClass('d-none');
            } 
        });

        $('.o-type').change(function() {
           let selected = $('input[name="order_type"]:checked').val();
           if(selected === "flexible") {
              $('#order-table').removeClass('d-none'); //console.log('set ');
              $('#lecture-section-for-flexible').removeClass('d-none'); 
           } else { 
              $('#order-table').addClass('d-none');
              $('#lecture-section-for-flexible').addClass('d-none'); 
           }
        });
        $('.lecture-o-type').change(function() {
           let selected = $('input[name="lecture_order_type"]:checked').val();
           if(selected === "flexible") {
              $('#lecture-order-table').removeClass('d-none'); 
           } else { 
              $('#lecture-order-table').addClass('d-none');
           }
        });
        
        $('.emp').draggable({
          revert: "invalid", 
          containment: "window",
          cursor: "move",
          start: function(event, ui){
            $(this).addClass('dragged');
          },
          stop: function(event, ui){
            $(this).removeClass('dragged');
          }
        });

        $('.job-emp').droppable({
          accept: '.emp',
          activeClass: 'active',
          drop: function(event, ui){
            ui.draggable.addClass('dropped');
            ui.draggable.detach().appendTo($(this));
          }
        });

        // $('.vhc').draggable({
        //   revert: "invalid", 
        //   containment: "window",
        //   cursor: "move",
        //   start: function(event, ui){
        //     $(this).addClass('dragged');
        //   },
        //   stop: function(event, ui){
        //     $(this).removeClass('dragged');
        //   }
        // });

        // $('.job-vhc').droppable({
        //   accept: '.vhc',
        //   activeClass: 'active',
        //   drop: function(event, ui){
        //     ui.draggable.addClass('dropped');
        //     ui.draggable.detach().appendTo($(this));
        //   }
        // });

</script>

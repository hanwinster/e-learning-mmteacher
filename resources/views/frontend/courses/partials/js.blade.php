
<script>
   
    $(document).ready(function () {
        var courseCategories = [];
        var selectedCat = "{{ $selectedCategory }}";
        if(selectedCat) courseCategories.push(selectedCat);
        var courseLevels = [];
        var keyword = $('#keyword').val();
        var currentLang =$('html').attr('lang'); 
        var domain = "{{ env('APP_ENV') }}" != 'localLocalhost' ? "{{ env('APP_URL') }}": "http://127.0.0.1:8000"; 
        var url = domain + "/" + currentLang + "/e-learning/courses"; //console.log(url);
        var translations = {
                            home: "@lang('Home')",
                            noCourseFound: "@lang('No Course Found!')",
                            "updatedAt": "@lang('Updated at')",    
                            Beginner: "@lang('Beginner')",
                            'Pre-intermediate': "@lang('Pre-intermediate')",
                            Intermediate: "@lang('Intermediate')", 
                            Advanced: "@lang('Advanced')",
                            Professional: "@lang('Professional')",
                            // "ICT-Pedagogy Integration": "@lang('ICT-Pedagogy Integration')",
                            // "Curriculum and Pedagogy": "@lang('Curriculum and Pedagogy')",
                            // "Curriculum and Pedagogy ": "@lang('Curriculum and Pedagogy')",
                            // "Media and Information Literacy (MIL)": "@lang('Media and Information Literacy (MIL)')",
                            // "Information and Communications Technology (ICT)": "@lang('Information and Communications Technology (ICT)')",
                            // "Continuous Professional Development": "@lang('Continuous Professional Development')", 
                            // "Comprehensive Sexuality Education (CSE)": "@lang('Comprehensive Sexuality Education (CSE)')",
                            // "Education for Peace and Sustainable Development (EPSD)": "@lang('Education for Peace and Sustainable Development (EPSD)')",
                            // "ICT-Pedagogy Integration, ": "@lang('ICT-Pedagogy Integration')",
                            // "Curriculum and Pedagogy, ": "@lang('Curriculum and Pedagogy')",
                            // "Media and Information Literacy (MIL), ": "@lang('Media and Information Literacy (MIL)')",
                            // "Information and Communications Technology (ICT), ": "@lang('Information and Communications Technology (ICT)')",
                            // "Continuous Professional Development, ": "@lang('Continuous Professional Development')", 
                            // "Comprehensive Sexuality Education (CSE), ": "@lang('Comprehensive Sexuality Education (CSE)')",
                            // "Education for Peace and Sustainable Development (EPSD), ": "@lang('Education for Peace and Sustainable Development (EPSD)')",                
        };
        
        ajaxCall(courseCategories, courseLevels, keyword, url); // on page load

        $('body').on('click', '.pagination li a', function (e) { //click on pagination links
            e.preventDefault();
            let url = $(this).attr('href');
            //console.log(courseCategories, courseLevels, keyword, url);
            ajaxCall(courseCategories, courseLevels, keyword, url);
        });
        //Added code to work with Bootstrap 5 dropdown but need to replace it with multi select
        function perpreArrayForFiltering(courseCategory, courseLevel) {
            // let courseCategories = [];
            // let courseLevels = [];
            if(courseCategory)  {
                courseCategories = [];
                courseCategories.push(courseCategory);
            }
            if(courseLevel)  {
                courseLevels = [];
                courseLevels.push(courseLevel);
            }          
            ajaxCall(courseCategories, courseLevels, keyword, url);
        }
        $(".category-filter-buttons").click(function() { 
            //var inputName = this.name;
            //var inputValue = $(this).text().replace(/\s/g, '');
            let selectedId = $(this).val();           
            $(".category-filter-buttons").map(function() {
                $(this).css('background-color','#0075D1');
            });
            $(this).css('background-color','#034cb9');
            perpreArrayForFiltering(selectedId, null);
        });
        $("#level-filter li a").click(function() { //console.log($(this).attr("id"));
            $("#level-filter-dd .btn:first-child").text($(this).text()); 
            $("#level-filter-dd .btn:first-child").val($(this).text());
            let selectedId = $(this).attr("id");
            let temp = selectedId.split('-');
            perpreArrayForFiltering(null, temp[1]);
        });
        //end of Added code to work with Bootstrap 5 dropdown but need to replace it with multi select
        $('.course-filter').on('change', function () {
            courseCategories = [];
            courseLevels = [];
            //console.log('changed course-filter ', $(this).text() );
            $('input[name="course_category"]:checked').each(function() {
                courseCategories.push(this.value);
            });
            $('input[name="course_level"]:checked').each(function() {
                courseLevels.push(this.value);
            });

            ajaxCall(courseCategories, courseLevels, keyword, url);
        });

        $('#keyword').keyup(function () {
            keyword = $('#keyword').val();
            if(keyword.length > 1) {
                ajaxCall(courseCategories, courseLevels, keyword, url);
            }           
        });

        function ajaxCall(courseCategories, courseLevels, keywords='', url) { 
                //console.log('about to search for ', keywords, url);
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json',
                data: {courseCategories: courseCategories, courseLevels: courseLevels, keyword: keywords},
                beforeSend: function() {
                    $('.course-info').html(
                        '<div class="row text-center">'+
                            '<div class="col-12">'+
                                '<div class="fa-5x">'+
                                    '<i class="fas fa-spinner fa-spin"></i>'+
                                '</div>'+
                            '</div>'+
                        '</div>'
                    );
                },
                success: function(response) {
                   // console.log('success response is ', response);
                    let courseInfo = [];
                    let paginationList = [];

                    if(response.data.length === 0) {
                        $('.pagination').css("display", "none");
                        $('.course-info').html(
                            '<div class="row mt-5 text-center">' +
                                '<div class="col"><h3>'+translations.noCourseFound+'</h3></div>' +
                            '</div>'
                        );
                        // $('#copyright-footer').addClass('stick-bottom');
                    } else {
                        $('.pagination').css("display", "flex");
                        // $('#copyright-footer').removeClass('stick-bottom');
                        let nextUrl = response.links.next;
                        let prevUrl = response.links.prev;

                        if(nextUrl) {
                            $('#next_url').attr('href', nextUrl);
                        }

                        if(prevUrl) {
                            $('#previous_url').attr('href', prevUrl);
                        } else {
                            $('#previous_url').attr('href', response.links.first)
                        }

                        for (let i = 0; i < response.data.length; i++) { //console.log(response.data);//.data, .meta, .links
                            let courseUrl = "{{ url('/e-learning/courses') }}/" + response.data[i].slug;
                            //let level = response.data[i].course_level.toString();
                            //let translatedLevel = translations.levels[level]; console.log(translatedLevel);
                            courseInfo.push(
                                '<div class="row mt-3 mb-3">\n' +
                                '   <div class="col-12">\n'+
                                '       <div class="card border-shadow-box">\n' +
                                '            <div class="row">\n' +
                                '                <div class="col-12 col-md-3 col-lg-4">\n' +
                                '                    <img src="' + response.data[i].cover_image + '"\n' +
                                '                         alt=""\n' +
                                '                         class="img-fluid rounded-start"\n' +
                                '                    >\n' +
                                '                </div>\n' +
                                '                <div class="col-12 col-md-9 col-lg-8 pl-2">\n' +
                                '                   <div class="card-body">\n' +
                                '                       <h5 class="card-title"><a href="' + courseUrl +'">' + response.data[i].title + '</a></h5>\n' +
                                '                       <p class="course-price">'+ response.data[i].course_category +'\n' +
                                '                       <span class="course-banner">'+ translations[response.data[i].course_level] +'</span></p>\n' +
                                '                       <p class="card-text">'+response.data[i].description.substring(0, 220)+ "..."+'</p>'+
                                '                       <p class="mt-1"><small class="text-muted"><i>'+translations.updatedAt+'&nbsp;</i>'+response.data[i].updated_at+'</small></p>'+                      
                                '                   </div>\n' +
                                '                </div>\n' +
                                '            </div>\n' +
                                '        </div>'+
                                '    </div>\n' +
                                ' </div>' 
                            );
                        }

                        for(let i = 1; i <= response.meta.last_page; i++) {
                            let pageId = 'page' + i;
                            let pageUrl = response.meta.path + '?page=' + i;

                            paginationList.push(
                                '<li class="page-item"><a class="page-link"'
                                + ' id=' + pageId +' href="' + pageUrl + '">' + i + '</a></li>'
                            );
                        }
                        $('.course-info').html(courseInfo);
                        $('.pagination-list').html(paginationList);

                        let currentPage = "page" + response.meta.current_page;
                        $('#' + currentPage).css('background-color', '#000000');
                    }
                },
                error: function (response) {
                    $('#copyright-footer').addClass('stick-bottom');
                    if(response.status === 500) {
                        $('.course-info').html(
                            '<div class="row text-center mt-5"><div class="col"><h3>500 Server Error! Sorry, We Will Fix Error Soon, Please Try Again Later!</h3></div></div>'
                        );
                    } else {
                        console.log("error response ", response);
                        $('.course-info').html(
                            '<div class="row text-center mt-5"><div class="col"><h3>Please Check Your Connection And Try Later!</h3></div></div>'
                        );
                    }
                }

            });
        }
    });
</script>

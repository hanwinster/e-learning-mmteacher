<!-- Head -->
@include('frontend.home.partials.header')
<!-- /.Head --> 



  <!-- ======= Hero Section ======= -->
  <section id="hero" class="d-flex justify-cntent-center align-items-center">
    <div id="heroCarousel" data-bs-interval="5000" class="container carousel carousel-fade" data-bs-ride="carousel">

      <!-- Slide 1 -->
      <div class="carousel-item active">
        <div class="carousel-container">
          <h3 class="animate__animated animate__fadeInDown text-white">@lang('Welcome to Myanmar Teacher Platform')</h3>
          <p class="animate__animated animate__fadeInUp">@lang('Myanmar Teacher Platform is intended to serve as an online, mobile and offline platform for educators, learners and other interested parties to access quality learning materials, videos and other resources in a safe and secure environment.')</p>
          <a href="#news" class="btn-get-started animate__animated animate__fadeInUp scrollto">@lang('Read More')</a>
        </div>
      </div>

      @foreach($newsAndEvents as $ne)
        <!-- Slide n -->
        <div class="carousel-item">
          <div class="carousel-container">
            <h5 class="animate__animated animate__fadeInDown text-white">
                @if($currentLang == 'en')
                  {{ $ne['title'] }}
                @else 
                  {{ $ne['title_mm'] ? $ne['title_mm'] : $ne['title'] }}
                @endif
            </h5>
            <p class="animate__animated animate__fadeInUp">
              @if($currentLang == 'en')
                {{ str_limit( strip_tags($ne['body']), 200, '...') }}
              @else 
                {{ $ne['body_mm'] ? str_limit( strip_tags($ne['body_mm']), 180, '...') : 
                  str_limit( strip_tags($ne['body']), 200, '...')  }}
              @endif
            </p>
            <a href="#news" class="btn-get-started animate__animated animate__fadeInUp scrollto">@lang('Read More')</a>
          </div>
        </div>
        <!-- End of slide n -->
      @endforeach

      <a class="carousel-control-prev" href="#heroCarousel" role="button" data-bs-slide="prev">
        <span class="carousel-control-prev-icon bx bx-chevron-left" aria-hidden="true"></span>
      </a>

      <a class="carousel-control-next" href="#heroCarousel" role="button" data-bs-slide="next">
        <span class="carousel-control-next-icon bx bx-chevron-right" aria-hidden="true"></span>
      </a>

    </div>
  </section><!-- End Hero -->

  <main id="main">

    <!-- ======= E-library E-learning Section ======= -->
    <section id="icon-boxes" class="icon-boxes">
      <div class="container">

        <div class="row">

          <div class="col-md-6 col-lg-6 d-flex align-items-stretch mb-5 mb-lg-0" data-aos="fade-up" data-aos-delay="100">
            <div class="icon-box">
              <div class="icon"><i class="bx bx-library"></i></div>
              <h4 class="title"><a href="https://lib.mmteacherplatform.net/{{ App::getLocale() }}">@lang('E-library')</a></h4>
              <p class="description">@lang('E-library contains over 200 electronic resources in various digital formats (E-books, videos, etc.).The E-library is integrated with the E-learning platform and thus help learners to experience an organized and innovative mode of learning.')
                &nbsp;<a href="https://lib.mmteacherplatform.net/{{ App::getLocale() }}" class="btn-get-started animate__animated animate__fadeInUp scrollto">@lang('Learn More')</a>
              </p>
            </div>
          </div>

          <div class="col-md-6 col-lg-6 d-flex align-items-stretch mb-5 mb-lg-0" data-aos="fade-up" data-aos-delay="300">
            <div class="icon-box">
              <div class="icon"><i class="bx bx-book-reader"></i></div>
              <h4 class="title"><a href="{{env('APP_URL').'/'.App::getLocale() }}/e-learning">@lang('E-learning')</a></h4>
              <p class="description">@lang('E-learning platform offers free courses through synchronous and asynchronous learning, where learners also have the opportunity to interact with each other via discussion forums.Learners can also gain certificate on the completion of the selected courses') 
                &nbsp;<a href="{{env('APP_URL').'/'.App::getLocale() }}/e-learning" class="btn-get-started animate__animated animate__fadeInUp scrollto">@lang('Learn More')</a>
              </p>
            </div>
          </div>

        </div>

      </div>
    </section><!-- End Icon Boxes Section -->

    <!-- ======= News & Events Section ======= -->
    <section id="news" class="team section-bg">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>@lang('News & Events')</h2>
          <p>@lang('Our news and events')</p>
        </div>

        <div class="row">

          @foreach($newsAndEvents as $ne)
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
              <div class="member d-flex align-items-start">
                <div class="pic">
                  <img src="{{ asset($ne['image_url']) }}" class="img-fluid" alt="">
                </div>
                <div class="member-info">
                  <h4>
                    @if($currentLang == 'en')
                      {{ $ne['title'] }}
                    @else 
                      {{ $ne['title_mm'] ? $ne['title_mm'] : $ne['title'] }}
                    @endif                  
                  </h4>
                  <span class="team-blue">
                    <i class="bi bi-person-lines-fill"></i>&nbsp;@lang('UNESCO Myanmar'), 
                    <i class="bi bi-clock"></i>&nbsp;{{ $ne['modified'] }}</li>
                  </span>
                  <p>
                    @if($currentLang == 'en')
                      {{ str_limit( strip_tags($ne['body']), 400, '...') }}
                    @else 
                      {{ $ne['body_mm'] ? str_limit( strip_tags($ne['body_mm']), 400, '...') : str_limit( strip_tags($ne['body']), 400, '...')  }}
                    @endif
                   
                    <a href="{{ $ne['hyperlink'] }}" 
                      class="btn-get-started animate__animated animate__fadeInUp scrollto">@lang('Learn More')</a>
                  </p>
                </div>
              </div>
            </div>
          @endforeach  

        </div> <!-- end of .row -->

      </div>
    </section>
    <!-- End Event Section -->
    <!-- ======= Resources Section ======= -->
    <section id="resources" class="team section-bg"> 
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>@lang('Resources')</h2>
          <h4>@lang('Curriculum and Pedagogy')</h4>
        </div>

        <div class="row">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <div class="member d-flex align-items-start">
              <div class="pic"><img src="https://mmteacherplatform.net/storage/9456/Y2-S1-EN-MTP-cover-final.png" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h5 class="text-primary">@lang('ICT Integration for Lesson Planning')-@lang('English Version')</h5>
                <span class="team-blue">
                  <i class="bi bi-person-lines-fill"></i>&nbsp;UNESCO Myanmar, 
                  <i class="bi bi-clock"></i>&nbsp;Oct 14, 2022</li>
                </span>
                <p>{{ str_limit("The new pre-service teacher education curriculum is designed to make best use of ICT across all subjects and hence Non-ICT teacher educators in Education", 200, '...') }}
                  <a href="https://mmteacherplatform.net/en/e-learning/courses/ict-integration-for-lesson-planning" 
                    class="btn-get-started animate__animated animate__fadeInUp scrollto">@lang('Learn More</a>
                </p>
              </div>
            </div>
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
            <div class="member d-flex align-items-start">
              <div class="pic"><img src="https://mmteacherplatform.net/storage/9465/Cover-Photo-of-ICT-Integration-for-Lesson-Planning-Course_MM.png" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h5 class="text-primary">@lang('ICT Integration for Lesson Planning')-@lang('Myanmar Version')</h5>
                <span class="team-blue">
                  <i class="bi bi-person-lines-fill"></i>&nbsp;UNESCO Myanmar, 
                  <i class="bi bi-clock"></i>&nbsp;Sept 28, 2022</li>
                </span>
                <p>{{ str_limit("The new pre-service teacher education curriculum is designed to make best use of ICT across all subjects and hence Non-ICT teacher educators in Education", 200, '...') }}
                  <a href="https://mmteacherplatform.net/en/e-learning/courses/p-span-style-font-family-pyidaungsu-thinpymhuponsanyaesawepyinsainyaatwin-ict-ko-paungsattanyatthwinkhyin-nbsp-span-span-style-font-family-pyidaungsu-span-p" 
                    class="btn-get-started animate__animated animate__fadeInUp scrollto">Learn More</a>
                </p>
        
              </div>
            </div>
          </div>
          <div class="col-lg-6 mt-4 mt-lg-3" data-aos="fade-up" data-aos-delay="200">
            <div class="member d-flex align-items-start">
              <div class="pic"><img src="https://lib.mmteacherplatform.net/storage/6558/Year-1-Local-Curriculum-Syllabus-%28MM-version%29.png" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h5 class="text-primary">@lang('EDC Year 1 Local Curriculum Syllabus')-@lang('Myanmar version')</h5>
                <span class="team-blue">
                  <i class="bi bi-person-lines-fill"></i>&nbsp;UNESCO Admin, 
                  <i class="bi bi-clock"></i>&nbsp;Sept 05, 2022</li>
                </span>
                <p> {{ str_limit("Regional curriculum subjects are part of the College of Education curriculum. This subject is included in Myanmar's basic education curriculum with the aim of preparing and training the subjects included in the regional curriculum subjects that can be taught.", 200, '...') }}
                <a href="https://lib.mmteacherplatform.net/en/resource/edc-year-1-local-curriculum-syllabus-myanmar-version" 
                  class="btn-get-started animate__animated animate__fadeInUp scrollto">Learn More</a>
                </p>
        
              </div>
            </div>
          </div>
          
          <div class="col-lg-6 mt-4 mt-lg-3" data-aos="fade-up" data-aos-delay="200">
            <div class="member d-flex align-items-start">
              <div class="pic"><img src="https://lib.mmteacherplatform.net/storage/5577/TCSF-cover-image.png" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h5 class="text-primary">@lang('Teacher Competency Standards Framework (TCSF)')-@lang('Myanmar version')</h5>
                <span class="team-blue">
                  <i class="bi bi-person-lines-fill"></i>&nbsp;UNESCO Admin, 
                  <i class="bi bi-clock"></i>&nbsp;Jul 25, 2022</li>
                </span>
                <p>{{ str_limit("This document, Myanmar’s Teacher Competency Standards Framework (TCSF), describes what are considered the key attributes of good teaching and what is expected of teachers’ professional practice at the start of their professional development.", 200, '...') }}
                <a href="https://lib.mmteacherplatform.net/en/resource/teacher-competency-standards-framework-tcsf" 
                  class="btn-get-started animate__animated animate__fadeInUp scrollto">Learn More</a>
                </p>
        
              </div>
            </div>
          </div>

        </div> <!-- end of .row Curriculum -->
         
        <div class="section-title mt-3" id="epsd">
          <h4>@lang('EPSD') - @lang('Education for Peace and Sustainable Development')</h4>
        </div>

        <div class="row">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <div class="member d-flex align-items-start">
              <div class="pic"><img src="https://mmteacherplatform.net/storage/8970/Cover-photo.png" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h5 class="text-primary">@lang('Education for Peace and Sustainable Development Online Lesson Module (1) Introduction to Sustainable Development')</h5>
                <span class="team-blue">
                  <i class="bi bi-person-lines-fill"></i>&nbsp;UNESCO Myanmar, 
                  <i class="bi bi-clock"></i>&nbsp;Sept 18, 2022</li>
                </span>
                <p>{{ str_limit("This online course can be studied in 5 modules; (1) an introduction to sustainable development, (2) the importance of education for peace and sustainable development, (3) knowledge standards, (4) teaching long-term development topics in school and (5) school-wide approach.", 200, '...') }}
                  <a href="https://mmteacherplatform.net/en/e-learning/courses/ngyeinkhyaanyae-nhngat-yaeyahi-pawanpayototetmhuko-paawsaaungthaw-pnyayae-aunlaing-thinkhansa" 
                    class="btn-get-started animate__animated animate__fadeInUp scrollto">Learn More</a>
                </p>
                <!-- <div class="social">
                  <a href=""><i class="ri-twitter-fill"></i></a>
                  <a href=""><i class="ri-facebook-fill"></i></a>
                  <a href=""><i class="ri-instagram-fill"></i></a>
                  <a href=""> <i class="ri-linkedin-box-fill"></i> </a>
                </div> -->
              </div>
            </div>
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
            <div class="member d-flex align-items-start">
              <div class="pic"><img src="https://mmteacherplatform.net/storage/8973/Cover-photo.png" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h5 class="text-primary">@lang('Education for Peace and Sustainable Development Online Lesson Module (2) Introduction to Education for Peace and Sustainable Development')</h5>
                <span class="team-blue">
                  <i class="bi bi-person-lines-fill"></i>&nbsp;UNESCO Myanmar, 
                  <i class="bi bi-clock"></i>&nbsp;Sept 28, 2022</li>
                </span>
                <p>{{ str_limit("This online course can be studied in 5 modules; (1) an introduction to sustainable development, (2) the importance of education for peace and sustainable development, (3) knowledge standards, (4) teaching long-term development topics in school and (5) school-wide approach.", 200, '...') }}
                  <a href="https://lib.mmteacherplatform.net/my-MM/resource/education-for-sustainable-development-teacher-guide-grade-4-1" 
                    class="btn-get-started animate__animated animate__fadeInUp scrollto">Learn More</a>
                </p>
        
              </div>
            </div>
          </div>
          <div class="col-lg-6 mt-4 mt-lg-3" data-aos="fade-up" data-aos-delay="200">
            <div class="member d-flex align-items-start">
              <div class="pic"><img src="https://lib.mmteacherplatform.net/storage/8529/pic6.JPG" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h5 class="text-primary">@lang('Education for Sustainable Development Teacher Guide Grade 4')- @lang('Myanmar version')</h5>
                <span class="team-blue">
                  <i class="bi bi-person-lines-fill"></i>&nbsp;UNESCO Admin, 
                  <i class="bi bi-clock"></i>&nbsp;Sept 05, 2022</li>
                </span>
                <p>@lang('Education for Sustainable Development Teacher Guide Grade 4')- @lang('Myanmar version')
                <a href="https://lib.mmteacherplatform.net/my-MM/resource/education-for-sustainable-development-teacher-guide-grade-4-1" 
                  class="btn-get-started animate__animated animate__fadeInUp scrollto">Learn More</a>
                </p>       
              </div>
            </div>
          </div>
          

          <div class="col-lg-6 mt-4 mt-lg-3" data-aos="fade-up" data-aos-delay="200">
            <div class="member d-flex align-items-start">
              <div class="pic"><img src="https://lib.mmteacherplatform.net/storage/8402/PE.JPG" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h5 class="text-primary">@lang('Building A culture of Peace Trainer Guide') @lang('Myanmar version')</h5>
                <span class="team-blue">
                  <i class="bi bi-person-lines-fill"></i>&nbsp;UNESCO Admin, 
                  <i class="bi bi-clock"></i>&nbsp;Jul 25, 2022</li>
                </span>
                <p>@lang('Building A culture of Peace Trainer Guide') @lang('Myanmar version')
                <a href="https://lib.mmteacherplatform.net/my-MM/resource/building-a-culture-of-peace-traner-guide-mm-version" 
                  class="btn-get-started animate__animated animate__fadeInUp scrollto">Learn More</a>
                </p>
        
              </div>
            </div>
          </div>

        </div> <!-- end of .row EPSD -->

        <div class="section-title mt-3" id="ict">
          <h4>@lang('ICT') - @lang('Information and Communications Technology')</h4>
        </div>

        <div class="row">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <div class="member d-flex align-items-start">
              <div class="pic"><img src="https://mmteacherplatform.net/storage/8506/viber-cover.JPG" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h5 class="text-primary">@lang('Online teaching and learning part (1), methods of communication among teachers and students')</h5>
                <span class="team-blue">
                  <i class="bi bi-person-lines-fill"></i>&nbsp;UNESCO Myanmar, 
                  <i class="bi bi-clock"></i>&nbsp;Oct 18, 2022</li>
                </span>
                <p>{{ str_limit("In order to make the best use of ICT in all subjects of the College of Education, a new teacher education curriculum has been developed", 200, '...') }}
                  <a href="https://mmteacherplatform.net/e-learning/courses/p-style-text-align-center-font-color-000000-style-span-style-font-family-pyidaungsu-b-style-aunlaingthinkyathinyumhu-apaing-1-b-span-font-p-p-style-text-align-center-font-color-000000-style-span-style-font-family-pyidaungsu-b-style-sayaanhngat-kyaaungthaakya-pyankyasaetthweyae-nilanmyaa-b-span-font-p" 
                    class="btn-get-started animate__animated animate__fadeInUp scrollto">Learn More</a>
                </p>
              </div>
            </div>
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
            <div class="member d-flex align-items-start">
              <div class="pic"><img src="https://mmteacherplatform.net/storage/8725/Capture.JPG" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h5 class="text-primary">@lang('Online teaching and learning part (2), using a mobile application to create instructional videos')</h5>
                <span class="team-blue">
                  <i class="bi bi-person-lines-fill"></i>&nbsp;UNESCO Myanmar, 
                  <i class="bi bi-clock"></i>&nbsp;Oct 28, 2022</li>
                </span>
                <p>{{ str_limit("This module will focus on teaching students the skills needed to create digital learning materials online. As teachers, online/remote", 200, '...') }}
                  <a href="https://mmteacherplatform.net/e-learning/courses/p-using-excel-to-create-reports-for-beginner-nbsp-p-p-akhyekhan-lelathumyaa-atwet-asiyainkhansamyaa-paantiyaan-excel-ko-athonpyukhyin-span-style-white-space-pre-span-p-div-br-div" 
                    class="btn-get-started animate__animated animate__fadeInUp scrollto">Learn More</a>
                </p>
        
              </div>
            </div>
          </div>
          <div class="col-lg-6 mt-lg-3" data-aos="fade-up" data-aos-delay="100">
            <div class="member d-flex align-items-start">
              <div class="pic"><img src="https://lib.mmteacherplatform.net/storage/2866/ICT-TB_Cover-Photo.png" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h5 class="text-primary">@lang('ICT Student Teacher Textbook Year 1 Semester 1')-@lang('English Version')</h5>
                <span class="team-blue">
                  <i class="bi bi-person-lines-fill"></i>&nbsp;UNESCO Myanmar, 
                  <i class="bi bi-clock"></i>&nbsp;May 5, 2022</li>
                </span>
                <p>{{ str_limit("The purpose of this course is to provide student teachers with basic knowledge of ICTrelated concepts and using ICT for teaching, learning and professional development and to prepare them to teach ICT in middle schools and in primary schools.", 200, '...') }}
                  <a href="https://lib.mmteacherplatform.net/en/resource/ict-student-teacher-book" 
                    class="btn-get-started animate__animated animate__fadeInUp scrollto">Learn More</a>
                </p>
              </div>
            </div>
          </div>
          <div class="col-lg-6 mt-lg-3" data-aos="fade-up" data-aos-delay="200">
            <div class="member d-flex align-items-start">
              <div class="pic"><img src="https://lib.mmteacherplatform.net/storage/8342/assessment.JPG" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h5 class="text-primary">@lang('ICT for online assessment')</h5>
                <span class="team-blue">
                  <i class="bi bi-person-lines-fill"></i>&nbsp;UNESCO Myanmar, 
                  <i class="bi bi-clock"></i>&nbsp;Oct 28, 2022</li>
                </span>
                <p>{{ str_limit("ICT for online assessment", 200, '...') }}
                  <a href="https://lib.mmteacherplatform.net/en/resource/ict-for-online-assessment" 
                    class="btn-get-started animate__animated animate__fadeInUp scrollto">Learn More</a>
                </p>
        
              </div>
            </div>
          </div>

        </div> <!-- end of .row ICT -->
                
        <div class="section-title mt-3" id="mil">
          <h4>@lang('MIL') - @lang('Media and Information Literacy')</h4>
        </div>

        <div class="row">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <div class="member d-flex align-items-start">
              <div class="pic"><img src="https://mmteacherplatform.net/storage/8037/intro_to_MIL.png" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h5 class="text-primary">@lang('Introduction to Media and Information Literacy')</h5>
                <span class="team-blue">
                  <i class="bi bi-person-lines-fill"></i>&nbsp;UNESCO Myanmar, 
                  <i class="bi bi-clock"></i>&nbsp;Oct 15, 2022</li>
                </span>
                <p>{{ str_limit("This course is designed to introduce learners to basic Media and Information Literacy concepts including the definitions, individual and societal rights, and MIL competencies  Media and information literacy", 200, '...') }}
                  <a href="https://mmteacherplatform.net/e-learning/courses/introduction-to-media-and-information-literacy-mooc" 
                    class="btn-get-started animate__animated animate__fadeInUp scrollto">Learn More</a>
                </p>
              </div>
            </div>
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
            <div class="member d-flex align-items-start">
              <div class="pic"><img src="https://mmteacherplatform.net/storage/8725/Capture.JPG" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h5 class="text-primary">@lang('Media and Information Literacy (with focus on Digital Literacy)')</h5>
                <span class="team-blue">
                  <i class="bi bi-person-lines-fill"></i>&nbsp;UNESCO Myanmar, 
                  <i class="bi bi-clock"></i>&nbsp;Oct 28, 2022</li>
                </span>
                <p>{{ str_limit("This module will focus on teaching students the skills needed to create digital learning materials online. As teachers, online/remote", 200, '...') }}
                  <a href="https://mmteacherplatform.net/e-learning/courses/p-using-excel-to-create-reports-for-beginner-nbsp-p-p-akhyekhan-lelathumyaa-atwet-asiyainkhansamyaa-paantiyaan-excel-ko-athonpyukhyin-span-style-white-space-pre-span-p-div-br-div" 
                    class="btn-get-started animate__animated animate__fadeInUp scrollto">Learn More</a>
                </p>
        
              </div>
            </div>
          </div>
          <div class="col-lg-6 mt-lg-3" data-aos="fade-up" data-aos-delay="100">
            <div class="member d-flex align-items-start">
              <div class="pic"><img src="https://lib.mmteacherplatform.net/storage/2866/ICT-TB_Cover-Photo.png" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h5 class="text-primary">@lang('Media Literacy Activities')</h5>
                <span class="team-blue">
                  <i class="bi bi-person-lines-fill"></i>&nbsp;UNESCO Myanmar, 
                  <i class="bi bi-clock"></i>&nbsp;May 5, 2022</li>
                </span>
                <p>{{ str_limit("The purpose of this course is to provide student teachers with basic knowledge of ICTrelated concepts and using ICT for teaching, learning and professional development and to prepare them to teach ICT in middle schools and in primary schools.", 200, '...') }}
                  <a href="https://lib.mmteacherplatform.net/en/resource/media-literacy-activities" 
                    class="btn-get-started animate__animated animate__fadeInUp scrollto">Learn More</a>
                </p>
              </div>
            </div>
          </div>
          <div class="col-lg-6 mt-lg-3" data-aos="fade-up" data-aos-delay="200">
            <div class="member d-flex align-items-start">
              <div class="pic"><img src="https://lib.mmteacherplatform.net/storage/2396/Oral-Language_Cover-Page.png" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h5 class="text-primary">@lang('Five Components of Effective Oral Language')</h5>
                <span class="team-blue">
                  <i class="bi bi-person-lines-fill"></i>&nbsp;UNESCO Myanmar, 
                  <i class="bi bi-clock"></i>&nbsp;Jul 26, 2022</li>
                </span>
                <p>{{ str_limit("Oral Language is the child’s first, most important, and most frequently used structured medium of communication. It is the primary means through which each individual child will be enabled to structure, to evaluate, to describe and to control his/her experience.", 200, '...') }}
                  <a href="https://lib.mmteacherplatform.net/en/resource/five-components-of-effective-oral-language" 
                    class="btn-get-started animate__animated animate__fadeInUp scrollto">Learn More</a>
                </p>
        
              </div>
            </div>
          </div>
        </div> <!-- end of .row MIL -->
        
        <div class="section-title mt-3" id="cse">
          <h4>@lang('CSE') - @lang('Comprehensive Sexuality Education')</h4>
        </div>

        <div class="row">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <div class="member d-flex align-items-start">
              <div class="pic"><img src="https://mmteacherplatform.net/storage/8471/CSE-cover-photo.png" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h5 class="text-primary">@lang('HIV/AIDS and Sexuality Education Module (1) Healthy and Happy Life')</h5>
                <span class="team-blue">
                  <i class="bi bi-person-lines-fill"></i>&nbsp;UNESCO Myanmar, 
                  <i class="bi bi-clock"></i>&nbsp;Oct 18, 2022</li>
                </span>
                <p>{{ str_limit("Welcome to the CHIV AIDS Sexual and Reproductive Health online course. This online course is supported by funding from the United Budget Result and Accountability Framework, UNESCO", 200, '...') }}
                  <a href="https://mmteacherplatform.net/e-learning/courses/p-comprehensive-sexuality-education-cse-nbsp-p-1" 
                    class="btn-get-started animate__animated animate__fadeInUp scrollto">Learn More</a>
                </p>
              </div>
            </div>
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
            <div class="member d-flex align-items-start">
              <div class="pic"><img src="https://mmteacherplatform.net/storage/8471/CSE-cover-photo.png" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h5 class="text-primary">@lang('HIV/AIDS and Sexuality Education Module (2) Sexuality and Reproductive Health')</h5>
                <span class="team-blue">
                  <i class="bi bi-person-lines-fill"></i>&nbsp;UNESCO Myanmar, 
                  <i class="bi bi-clock"></i>&nbsp;Oct 18, 2022</li>
                </span>
                <p>{{ str_limit("Welcome to the CHIV AIDS Sexual and Reproductive Health online course. This online course is supported by funding from the United Budget Result and Accountability Framework, UNESCO", 200, '...') }}
                  <a href="https://mmteacherplatform.net/e-learning/courses/p-using-excel-to-create-reports-for-beginner-nbsp-p-p-akhyekhan-lelathumyaa-atwet-asiyainkhansamyaa-paantiyaan-excel-ko-athonpyukhyin-span-style-white-space-pre-span-p-div-br-div" 
                    class="btn-get-started animate__animated animate__fadeInUp scrollto">Learn More</a>
                </p>
        
              </div>
            </div>
          </div>
          <div class="col-lg-6 mt-lg-3" data-aos="fade-up" data-aos-delay="100">
            <div class="member d-flex align-items-start">
              <div class="pic"><img src="https://lib.mmteacherplatform.net/storage/8327/User%27-Guidebook.png" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h5 class="text-primary">@lang("CSE User' Guidebook")</h5>
                <span class="team-blue">
                  <i class="bi bi-person-lines-fill"></i>&nbsp;UNESCO Myanmar, 
                  <i class="bi bi-clock"></i>&nbsp;May 5, 2022</li>
                </span>
                <p>{{ str_limit("CSE User' Guidebook", 200, '...') }}
                  <a href="https://lib.mmteacherplatform.net/en/resource/cse-user-guidebook" 
                    class="btn-get-started animate__animated animate__fadeInUp scrollto">Learn More</a>
                </p>
              </div>
            </div>
          </div>
          <div class="col-lg-6 mt-lg-3" data-aos="fade-up" data-aos-delay="200">
            <div class="member d-flex align-items-start">
              <div class="pic"><img src="https://lib.mmteacherplatform.net/storage/4047/HIV-AIDS-facilitator-guidebook.JPG" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h5 class="text-primary">@lang('HIV/AIDS and Sexuality Education (Facilitator Guide)')</h5>
                <span class="team-blue">
                  <i class="bi bi-person-lines-fill"></i>&nbsp;UNESCO Myanmar, 
                  <i class="bi bi-clock"></i>&nbsp;Jun 06, 2022</li>
                </span>
                <p>{{ str_limit("This reference material is prepared for teacher educators and student teachers to do self-learning and also support for primary and middle level teaching life skills in school. It describes awareness about reproductive health, HIV/AIDS (knowledge and prevention) including gender.", 200, '...') }}
                  <a href="https://lib.mmteacherplatform.net/en/resource/five-components-of-effective-oral-language" 
                    class="btn-get-started animate__animated animate__fadeInUp scrollto">Learn More</a>
                </p>
        
              </div>
            </div>
          </div>
        </div> <!-- end of .row MIL -->

      </div>
    </section>             
    <!-- End Resources Section -->
    <!-- ======= Partners Section ======= -->
    <section id="partners" class="clients section-bg">
          <div class="container" data-aos="zoom-in">
            <div class="section-title">
              <h2>@lang('Our Partners')</h2>
            </div>
            <div class="clients-slider swiper">
              <div class="swiper-wrapper align-items-center">
                <div class="swiper-slide"><img src="{{ asset('assets/img/logos/UNESCO_logo_hor_blue.png') }}" class="img-fluid" alt=""></div>
                <div class="swiper-slide"><img src="{{ asset('assets/img/logos/logo-FORMIN.png') }}" class="img-fluid" alt=""></div>
                <!-- <div class="swiper-slide"><img src="https://lib.mmteacherplatform.net/assets/img/logos/logo-australian-aid.png" class="img-fluid" alt=""></div> -->
                <!-- <div class="swiper-slide"><img src="{{ asset('assets/img/logos/UNESCO_logo_hor_blue.png') }}" class="img-fluid" alt=""></div> -->
                <!-- <div class="swiper-slide"><img src="https://lib.mmteacherplatform.net/assets/img/logos/logo-dark@2x.png" class="img-fluid" alt=""></div> -->
              </div>
              <div class="swiper-pagination"></div>
            </div>

          </div>
    </section><!-- End Clients Section -->
  </main><!-- End #main -->

  <!-- Footer -->
@include('frontend.home.partials.footer')
<!-- /.footer -->

<!-- Head -->
@include('frontend.home.partials.header')
<!-- /.Head -->

  
  <main id="main">

    <!-- ======= Breadcrumbs ======= -->
    <section id="breadcrumbs" class="breadcrumbs">
      <div class="container">

        <ol>
          <li><a href="{{env('APP_URL').'/'.App::getLocale() }}/#hero">@lang('Home')</a></li>
          <li>@lang('Other Resources')</li>
        </ol>
       

      </div>
    </section><!-- End Breadcrumbs -->

    <section id="other-resources" class="team section-bg">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>@lang('Other Resources')</h2>
          <p>Teaching & Learning Resources</p>
        </div>

        <div class="row">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
            <div class="member d-flex align-items-start">
              <div class="pic"><img src="{{ asset('assets/img/logos/UNESCO_logo_hor_blue.png') }}" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h4>UNESCO Bangkok</h4>
                <p>{{ str_limit("Since 1961, UNESCO Bangkok has had a dual role as both the Asia and Pacific Regional Bureau for Education and as a Cluster Office in the Asia-Pacific region.
                    As a regional bureau for education, UNESCO Bangkok provides technical expertise and assistance, and it serves advisory, 
                    knowledge production and sharing, and monitoring and evaluation ", 200,  '...') }}
                <a href="https://bangkok.unesco.org/content/unesco-bangkok" 
                    class="btn-get-started animate__animated animate__fadeInUp scrollto">@lang('Learn More')</a>
                </p>
              </div>
            </div>
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <div class="member d-flex align-items-start">
              <div class="pic"><img src="/assets/img/photos/lll-opt.png" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h4>Lifelong Learning</h4>
                <p>{{ str_limit("With the support of the Government of Japan, UNESCO Bangkok together with partner organizations and experts from 11 countries across Asia-Pacific developed the Online Course on Community Learning Centres (CLCs) 
                  and Lifelong Learning in Asia and the Pacific with an aim to enhance capacity of government officials, CLC managers and facilitators, educators and students of lifelong learning.", 200,  '...') }}
                  <a href="https://lll-olc.net/en/about-us-ele/" 
                    class="btn-get-started animate__animated animate__fadeInUp scrollto">@lang('Learn More')</a>
                </p>
                
              </div>
            </div>
          </div>
          </div>
          <div class="row mt-4 mt-lg-3">        
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
            <div class="member d-flex align-items-start">
              <div class="pic"><img src="/assets/img/photos/tree-logo-opt.png" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h4>TREE</h4>
                <p>{{ str_limit("Towards Results in Education & English (TREE) is a collection of courses for learners in Myanmar. These materials were produced as part of
                   the TREE project led by the British Council and funded by FCDO / UKAid", 200,  '...') }}
                <a href="https://www.open.edu/openlearncreate/course/index.php?categoryid=401" 
                    class="btn-get-started animate__animated animate__fadeInUp scrollto">@lang('Learn More')</a>
                </p>
              </div>
            </div>
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
            <div class="member d-flex align-items-start">
              <div class="pic"><img src="{{ asset('assets/img/photos/learn-big-opt.png') }}" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h4>Learn Big</h4>
                <p>{{ str_limit("The multi-language platform “LearnBig” was developed as part of the UNESCO Bangkok’s initiative “Mobile Literacy for Out-of-School Children” supported by Microsoft, True Corporation, POSCO 1% Foundation and the Ministry of Education, Thailand.
                    The project aims to enhance basic literacy and numeracy skills of over 5,500 migrant, ethnic minority, 
                    stateless and marginalized children along the Thai-Myanmar border", 200, '...') }}
                <a href="https://www.learnbig.net/about/#Background" 
                    class="btn-get-started animate__animated animate__fadeInUp scrollto">@lang('Learn More')</a>
                </p>
              </div>
            </div>
          </div>
          </div>
          <div class="row mt-4 mt-lg-3">
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
              <div class="member d-flex align-items-start">
                <div class="pic"><img src="{{ asset('assets/img/photos/lp-opt.png') }}" class="img-fluid" alt=""></div>
                <div class="member-info">
                  <h5 style="color: #05579e;">UNICEF - Learning Passport</h5>
                  <p>{{ str_limit("The Learning Passport is an Online, Mobile, and Offline Platform that enables continuous access to quality education. 
                    It is highly flexible and adaptable, allowing countries to easily and quickly adopt the Learning Passport 
                    as its national learning management system or use it to complement existing digital learning platforms", 200, '...') }}
                  <a href="https://www.learningpassport.org/about-learning-passport" 
                      class="btn-get-started animate__animated animate__fadeInUp scrollto">@lang('Learn More')</a>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
              <div class="member d-flex align-items-start">
                <div class="pic"><img src="{{ asset('assets/img/photos/bc-logo-dark-blue.png') }}" class="img-fluid" alt=""></div>
                <div class="member-info">
                  <h5 style="color: #05579e;">British Council Myanmar</h5>
                  <p>{{ str_limit("The British Council is the United Kingdom's international cultural relations organisation founded in 1934.
                    We have offices in 229 towns and cities in 110 countries and territories worldwide. We have been in Myanmar since 1946.", 200, '...') }}
                  <a href="https://www.britishcouncil.org.mm" 
                      class="btn-get-started animate__animated animate__fadeInUp scrollto">@lang('Learn More')</a>
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="row mt-4 mt-lg-3">
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
              <div class="member d-flex align-items-start">
                <div class="pic"><img src="{{ asset('assets/img/photos/mote-oo-opt.png') }}" class="img-fluid" alt=""></div>
                <div class="member-info">
                  <h5 style="color: #05579e;">Mote Oo Education</h5>
                  <p>{{ str_limit("Mote Oo Education is an educational services provider. It was founded in 2013 to add value to post-secondary and adult education initiatives. 
                    We train teachers and trainers, design and develop curriculum, and create teaching and learning resources.
                    We work with schools and organisations in and from Myanmar.", 200, '...') }}
                    <a href="https://www.moteoo.org/en/teacher-education" 
                      class="btn-get-started animate__animated animate__fadeInUp scrollto">@lang('Learn More')</a>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
              <div class="member d-flex align-items-start">
                <div class="pic"><img src="/assets/img/photos/TF_Logo_vertical_white_bg.png" class="img-fluid" alt=""></div>
                <div class="member-info">
                  <h4>Teacher Focus</h4>
                  <p>{{ str_limit("To Advance Teacher Quality and Recognition for Marginalized Teachers from Myanmar Using Accessible and Contextual Resources.
                      Teaching is an incredibly demanding profession.
                      This is especially true for new teachers and teachers working in development contexts. With so much", 200,  '...') }}
                    <a href="https://www.teacherfocusmyanmar.org/teacher-resources" 
                      class="btn-get-started animate__animated animate__fadeInUp scrollto">@lang('Learn More')</a>
                  </p>
                  
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</section>
   
  </main><!-- End #main -->

<!-- Footer -->
@include('frontend.home.partials.footer')
<!-- /.footer -->
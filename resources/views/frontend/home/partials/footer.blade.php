<!-- ======= Footer ======= -->
<footer id="footer">

<div class="footer-top">
  <div class="container">
    <div class="row">

      <div class="col-lg-3 col-md-6 footer-links">
        <h4>Useful links</h4>
        <ul>
          <li><i class="bx bx-chevron-right"></i> <a href="{{env('APP_URL').'/'.App::getLocale() }}/#hero">@lang('Home')</a></li>
          <!-- <li><i class="bx bx-chevron-right"></i> <a href="https://lib.mmteacherplatform.net/en">E-library</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="https://mmteacherplatform.net/en">E-learning</a></li> -->
          <li><i class="bx bx-chevron-right"></i> <a href="#">@lang('Contact us')</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="{{ route('terms-privacy' ) }}">@lang('Terms and conditions')</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="{{ route('terms-privacy' ) }}/#privacy">@lang('Privacy policy')</a></li>
          <!-- <li><i class="bx bx-chevron-right"></i> <a href="https://www.open.edu/openlearncreate/course/index.php?categoryid=401">Towards Results in Education & English (TREE)</a></li> -->
        </ul>
      </div>

      <div class="col-lg-5 col-md-6 footer-links">
        <h4>@lang('Resources')</h4>
        <ul>
          <li><i class="bx bx-chevron-right"></i> <a href="{{env('APP_URL').'/'.App::getLocale() }}#resources">@lang('Curriculum and Pedagogy')</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="{{env('APP_URL').'/'.App::getLocale() }}#epsd">@lang('Education for Peace and Sustainable Development(EPSD)')</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="{{env('APP_URL').'/'.App::getLocale() }}#ict">@lang('Information and Communications Technology (ICT)')</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="{{env('APP_URL').'/'.App::getLocale() }}#mil">@lang('Media and Information Literacy (MIL)')</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="{{env('APP_URL').'/'.App::getLocale() }}#cse">@lang('Comprehensive Sexuality Education (CSE)')</a></li>         
          <li><i class="bx bx-chevron-right"></i> <a href="https://lib.mmteacherplatform.net/en/other-resources">@lang('Additional resources')</a></li>
        </ul>
      </div>

      <!-- <div class="col-lg-4 col-md-6 footer-links">
        {{-- <h4>@lang('E-learning')</h4> --}}
        <ul>
          {{-- <li><i class="bx bx-chevron-right"></i> <a href="{{env('APP_URL').'/'.App::getLocale() }}/e-learning/browse-category?category=1">@lang('Information and Communication Technology')</a></li> --}}
          {{-- <li><i class="bx bx-chevron-right"></i> <a href="{{env('APP_URL').'/'.App::getLocale() }}/e-learning/browse-category?category=4">@lang('Curriculum and Pedagogy')</a></li> --}}
          {{-- <li><i class="bx bx-chevron-right"></i> <a href="{{env('APP_URL').'/'.App::getLocale() }}/e-learning/browse-category?category=5">@lang('Media and Information Literacy (MIL)')</a></li> --}}
          {{-- <li><i class="bx bx-chevron-right"></i> <a href="{{env('APP_URL').'/'.App::getLocale() }}/e-learning//browse-category?category=10">@lang('Education for Peace and Sustainable Development (EPSD)')</a></li> --}}
          {{-- <li><i class="bx bx-chevron-right"></i> <a href="{{env('APP_URL').'/'.App::getLocale() }}/e-learning//browse-category?category=12">@lang('Comprehensive Sexuality Education (CSE)')</a></li> --}}
        </ul>
      </div> -->

      <div class="col-lg-2 col-md-6 footer-info"> 
        <h3>@lang('Follow us')</h3>
        <div class="social-links mt-3">           
          <a href="https://www.facebook.com/UNESCOMyanmar" class="facebook"><i class="bx bxl-facebook"></i></a>
          <a href="https://www.youtube.com/channel/UCWFMwfh_7JT29nwB1Czmuvg" class="youtube" alt="YouTube"><i class="bx bxl-youtube"></i></a>       
        </div>           
      </div>
      <div class="col-lg-2 col-md-6 footer-stores"> 
        <a href="https://apps.apple.com/us/app/myanmar-teacher-platform/id6444518874" target="_blank">
          <img src="{{ asset('assets/img/app-store.png') }}" height="48px"/>
        </a>
        <a href="https://play.google.com/store/apps/details?id=com.misfit.mtp&hl=en&gl=US" target="_blank">
          <img src="{{ asset('assets/img/play-store.png') }}" class="mt-2"  height="48px"/>
        </a>
      </div>

    </div>
  </div>
</div>

<div class="container">
  <div class="copyright">
    @lang('Copyright') &copy; @php echo date('Y'); @endphp &nbsp; <strong><span>Myanmar Teacher Platform</span></strong>. @lang('All Rights Reserved')
  </div>
</div>
</footer><!-- End Footer -->

<div id="preloader"></div>
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="/vendor/home-template/assets/vendor/aos/aos.js"></script>
<script src="/vendor/home-template/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/vendor/home-template/assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="/vendor/home-template/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
<script src="/vendor/home-template/assets/vendor/swiper/swiper-bundle.min.js"></script>
<!-- <script src="assets/vendor/php-email-form/validate.js"></script> -->

<!-- Template Main JS File -->
<script src="/vendor/home-template/assets/js/main.js"></script>

</body>

</html>
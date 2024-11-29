<?php

namespace App\Http\Controllers\Member;

use App\Http\Requests\RequestCertificate as RequestCertificate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Repositories\CourseRepository;
use App\Repositories\CertificateRepository;
use App\User;
use Carbon\Carbon;
use PDF;

class CertificateController extends Controller
{
    public function __construct(CertificateRepository $repository, CourseRepository $courseRepository)
    {
        $this->repository = $repository;
        $this->courseRepository = $courseRepository;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($course_id)
    {
        $course = $this->courseRepository->find($course_id);
       
        return view('frontend.member.certificate.form', compact('course'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\RequestAssignment  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestCertificate $request, $course_id)
    {
        $request->validated();
        $this->courseRepository->find($course_id);
        $this->repository->saveRecord($request);

        $id = $this->repository->getKeyId();

        if ($request->input('btnSaveNew')) {
            return redirect()->route('member.course.certificate.create', $course_id)
              ->with(
                  'success',
                  __('Certificate has been successfully saved. And you are ready to create a new assignment.')
              );
        } elseif ($request->input('btnSave')) {
            return redirect()->route('member.course.certificate.create', $id)
              ->with(
                  'success',
                  __('Certificate has been successfully saved.')
              );
        } else {
            return redirect(route('member.course.show', $course_id).'#nav-certificate')
              ->with(
                  'success',
                  __('Certificate has been successfully saved.')
              );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $certificate = $this->repository->find($id);
        return view('frontend.member.course.certificate.show', compact('certificate'));
    }

    /**
     * Display the user's assignment.
     *
     * @param  int  $course_id, int $assignment_id
     * @return \Illuminate\Http\Response
     */
    public function userCertificate($id)
    {
        // $certificate = $this->repository->find($id);
        // $user_certificates = CertificateUser::where('assignment_id', $id)->paginate();
        // return view('frontend.member.course.certificate.user_certificate', compact('certificate', 'user_certificates'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = $this->repository->find($id);
        $course = $this->courseRepository->find($post->course_id);
        return view('frontend.member.certificate.form', compact('course', 'post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\RequestAssignment  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequestCertificate $request, $id)
    {
        $request->validated();
        $certificate = $this->repository->find($id);
        $this->repository->saveRecord($request, $id);

        $id = $this->repository->getKeyId();

        if ($request->input('btnSave')) {
            return redirect()->route('member.course.certificate.edit', [$id])
              ->with(
                  'success',
                  __('Certificate has been successfully updated.')
              );
        } else {
            return redirect(route('member.course.show', $certificate->course_id).'#nav-certificate')
              ->with(
                  'success',
                  __('Certificate has been successfully updated.')
              );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = $this->repository->find($id);
       // $post->assignment_user->each->delete();
        $post->delete();
        return redirect(route('member.course.show', $post->course_id). '#nav-assignment')
          ->with('success', 'Successfully deleted');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePDF($id)
    {   
        $certificate = $this->repository->find($id);
        // $date = Carbon::parse(date());
        // $date->format('d-m-Y');
        $course = $this->courseRepository->find($certificate->course_id);
        $data = [
            'title' => $certificate->title,
            'certify' => $certificate->certify_text,
            'completion' => $certificate->completion_text,
            'isPreview' => true,
            'today' => date("d M Y"),
            'courseDes' => $course->description,
            'courseObj' => $course->objective,
            'courseOut' => $course->learning_outcome
        ];
        $courseOwner = User::findOrFail($certificate->course->user_id);
        if($courseOwner->type == "teacher_educator") {
            return view('frontend.certificates.template_1_pilot', $data );
        }
        return view('frontend.certificates.template_1_modified', $data );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadPDF($id)
    {   
        set_time_limit(3000); //loaded slow coz of external css
        $certificate = $this->repository->find($id);
        
        $data = [
            'title' => $certificate->title,
            'certify' => $certificate->certify_text,
            'completion' => $certificate->completion_text,
            'isPreview' => false,
            'today' => date("d/m/Y"),
            'logoText' => "Myanmar Teacher Platform"
        ];
            
        $courseOwner = User::findOrFail($certificate->course->user_id);
        if($courseOwner->type == "teacher_educator") {
            $pdf = PDF::loadView('frontend.certificates.template_1_pilot_pdf', $data)->setPaper('a4', 'landscape');  
        } else {
            // $pdf = PDF::loadView('frontend.certificates.template_1_pdf', $data);  
            //Get the default settings
            $options = PDF::getDomPDF()->getOptions();
            $options->set('isFontSubsettingEnabled', true);
            $options->set('defaultFont', 'Helvetica');
            $pdf = PDF::setPaper('a4', 'landscape');
            //Set the options, if we use the included function, the defaults are overwritten and this leads to weird behaviour.
            $pdf->getDomPDF()->setOptions($options);
            
            $pdf = PDF::loadView('frontend.certificates.template_1_pdf_modified', $data)->setPaper('a4', 'landscape')
                    ->setOptions(['defaultFont' => 'cloisterblack', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
           // dd($pdf->getDomPDF()->getOptions());exit;
           //$pdf->output();
        }
        return $pdf->download('certificate.pdf');
    }

}

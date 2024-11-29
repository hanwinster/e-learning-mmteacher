<div class="row">

    <!--<div class="col-md-6 mb-md-0">-->
    @php
    //$preview_images = $post->getMedia('resource_previews');
    @endphp
    @if($image->getCustomProperty('iframe'))
    

    <!------------- iframe ------------------>
   
    <!------------------------------->

    @elseif(substr($image->mime_type, 0, 5) == 'image')

    <div class="col-md-6 mb-md-0">
            <a href="{{ asset($image->getUrl()) }}" data-provide="lightbox" class="btn btn-primary btn-lg">{{__('Preview before download') }}</a>
    </div>

    <!------------------- Modal image -------------------------->
    <div class="modal fade" id="modal-image" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                    <img src="{{ asset($image->getUrl()) }}" class="shadow-4 rounded-lg">
                </div>

            </div>
        </div>
    </div>

    @elseif(in_array($image->mime_type, ['application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.oasis.opendocument.presentation',
    'application/vnd.oasis.opendocument.spreadsheet',
    'application/vnd.oasis.opendocument.text',
    'application/pdf',
    'application/vnd.ms-powerpoint',
    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    'application/rtf',
    'application/vnd.ms-excel',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
    )
    <div class="col-md-6 mb-md-0">
        @php
        $parts = parse_url($image->custom_properties['gdrive_link']);
        parse_str($parts['query'], $query);
        @endphp
        <a href="{{'https://drive.google.com/file/d/'.$query['id']}}/preview" data-provide="lightbox" class="btn btn-primary btn-lg">{{__('Preview before download') }}</a>
        
        <!-- <a href="{{$image->custom_properties['gdrive_link']}}/preview" data-provide="lightbox" class="btn btn-primary btn-lg">{{__('Preview before download') }}</a> -->
        <!-- <a href="https://docs.google.com/gview?url={{ asset($image->getUrl()) }}&embedded=true" data-provide="lightbox" class="btn btn-primary btn-lg">{{__('Preview before download') }}</a> -->
        <!-- <a href="https://docs.google.com/gview?url={{$image->custom_properties['gdrive_link']}}&embedded=true" data-provide="lightbox" class="btn btn-primary btn-lg">{{__('Preview before download') }}</a> -->
    </div>

    @elseif( $image->mime_type == 'application/pdf')
    <div class="col-md-6 mb-md-0">
        <a target="_blank" href="{{ asset($image->getUrl()) }}" data-toggle="modal" data-target="#modal-pdf">{{ $image->file_name }}</a>
    </div>

    <!------------- Modal pdf ------------------>
    <div class="modal fade" id="modal-pdf" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>


                    <div id="pdf">
                        <object width="100%" height="1100px" type="application/pdf" data="{{ asset($image->getUrl()) }}"></object>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <!------------------------------->
    @elseif(in_array(strtolower(pathinfo($image->getPath(), PATHINFO_EXTENSION)), ['mp3', 'wav']))
    <!-- Audio -->
    <div class="col-md-6 mb-md-0">
            <a href="{{ asset($image->getUrl()) }}" data-provide="lightbox" class="btn btn-primary btn-lg">{{__('Preview before download') }}</a>
    </div>
    <!-- End of Audio -->
    @else
    <div class="col-md-6 mb-md-0">
        <a target="_blank" href="{{ asset($image->getUrl()) }}">
                        {{--<img src="{{ asset($image->getUrl()) }}" class="shadow-4 rounded-lg">--}}
                        {{ $image->file_name }}
                    </a>
    </div>
    @endif
    <!--</div>-->

    @if($post->allow_download)
    @php
    $resources = $post->getMedia('resource_full_version_files');
    @endphp
    @foreach($resources as $resource)

    @if(isset($resource->custom_properties['gdrive_link']))

    <div class="col-md-5 mb-md-0 text-right">
        <a href="{{$resource->custom_properties['gdrive_link']}}" target="_blank" class="btn btn-lg btn-primary"><i class="fa fa-download" aria-hidden="true"></i>
            {{ __('Download') }}
        </a>

        {{ $resource->human_readable_size }}
    </div>
    @else
    <div class="col-md-5 mb-md-0 text-right">
        <a href="{{ route('resource.download', $resource) }}" class="btn btn-lg btn-primary"><i class="fa fa-download" aria-hidden="true"></i>
            {{ __('Download') }}
        </a>

        {{ $resource->human_readable_size }}
    </div>
    @endif
    @endforeach @endif
</div>
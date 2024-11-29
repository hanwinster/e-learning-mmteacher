
    <div class="container text-center">
        <p class="small-4 text-uppercase ls-2 fw-600 opacity-70">{{ __('eLibrary helps simplify the research process and empowers novice researchers to more easily.') }}</p>
        <h2>Ready to get started?</h2>

        
        {{ Form::open(array('route' => 'search.advanced', 'method' => 'get', 'class' => 'col-md-10 col-xl-10 input-glass mx-auto')) }}
		
		<div class="row col-lg-12 mt-3">
			<div class="col-lg-4 form-group"> 
				<select class="form-control" id="resource" name="resource">
				<option value="">-Select Formats -</option>
					@foreach($formats as $key => $row)
						<option value="{{ $key }}"> {{ $row }} </option>
					@endforeach
					</select>
			</div>
			<div class="col-lg-4 form-group">	
				<select class="form-control" id="subject" name="subject">
				<option value="">- Select Subjects -</option>
					@foreach($subjects as $key => $row)
						<option value="{{ $key }}"> {{ $row->title }} </option>
					@endforeach
					</select>
			</div>	
			<div class="col-lg-4 form-group">	
				<select class="form-control" id="subject" name="subject">
				<option value="">- Select Education College Year(s)-</option>
					@foreach ($years as $key => $year)
						<option value="{{ $key }}"> {{ $year }}</option>
					@endforeach
					</select>
			</div>		
			<div class="col-lg-4 form-group">	
				<select class="form-control" id="subject" name="subject">
				<option value="">- Select Accessible Right -</option>
					@foreach ($userTypes as $key => $value)
						<option value="{{ $key }}"> {{ $key }} </option>
					@endforeach
					</select>
			</div>
			<div class="col-lg-4 form-group">	
				<select class="form-control" id="subject" name="subject">
					@foreach($licenses as $key => $license)
						<option value="{{ $key }}"> {{ $license }} </option>
					@endforeach
					</select>
			</div>	
			<div class="col-lg-4 form-group">	
				<select class="form-control" id="subject" name="subject">
				<option value="">- Other Filter -</option>
				<option value="author"> Author </option>
				<option value="title">Title </option>
				<option value="strand">Strand</option>
				<option value="keyword">Keyword</option>
					
					</select>
			</div>
			
			</div>
			<div class="row col-lg-12 mt-3">
			<div class="col-lg-12 form-group">
				<div class="input-group input-group-lg1">
					
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                </div>
                <input type="text" name="search" class="form-control" placeholder="Enter your keyword" value="{{ request('search') }}">
                <span class="input-group-append">
                    <button class="btn btn-light">{{ __('Search') }}</button>
                </span>
            </div>
			</div>
		</div>
		

    </div>

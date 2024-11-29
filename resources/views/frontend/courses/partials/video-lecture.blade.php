<iframe width="100%" height="540" src="{{ $lecturesMedias->where('model_id', $currentSection->id)->first()->getUrl() }}" 
frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

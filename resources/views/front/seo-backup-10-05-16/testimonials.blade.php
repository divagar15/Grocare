@extends('front.layout.tpl')
@section('customCss')
  <style type="text/css">
    .panel-default{
      border-top:0px;
    }
	.tablist{
		background: #dddddd;
	}
  </style>

@endsection

@section('content')

<div class="container pageStart" style="margin-top:30px;">

<div class="row" id="testimonials">

  <div class="col-md-12">

    <h2>CUSTOMER TESTIMONIALS</h2>
      <ul role="tablist" class="nav nav-tabs tablist" id="myTab">
        <li class="active"><a data-toggle="tab" role="tab" href="#written">Written Testimonials</a></li>
        <li><a data-toggle="tab" role="tab" href="#audio">Audio Testimonials</a></li>
        <li><a data-toggle="tab" role="tab" href="#social">Published on Social Media</a></li>
      </ul>

      <div class="tab-content" id="myTabContent">
        <div id="written" class="tab-pane tabs-up fade in active panel panel-default">
          <div class="panel-body">
            @if(count($testimonials)>0)
              @foreach($testimonials as $testi)
                @if($testi->type == 1)
                  <p>{!! $testi->testi_content !!}</p>
                  <p><i>{{$testi->testi_from}}</i></p>
				  <hr>
                @endif
              @endforeach
            @endif            
          </div>
        </div>
        <div id="audio" class="tab-pane tabs-up fade panel panel-default">
          <div class="panel-body">
            <div class="col-md-12">
              @if(count($testimonials)>0)
                @foreach($testimonials as $testi)
                  @if($testi->type == 2)
                  <div class="col-md-4">
                    <audio src="{{URL::to('public/uploads/testimonials').'/'.$testi->testi_content}}" controls></audio>
                    <p><i>{{$testi->testi_from}}</i></p>
                  </div>                  
                  @endif
                @endforeach
              @endif
            </div>          
          </div>
        </div>
        <div id="social" class="tab-pane tabs-up fade panel panel-default">
          <div class="panel-body">
            <div class="col-md-12">
              @if(count($testimonials)>0)
                @foreach($testimonials as $testi)
                  @if($testi->type == 3)
                    <img src="{{URL::to('public/uploads/testimonials').'/'.$testi->testi_content}}">
                    <p><i>{{$testi->testi_from}}</i></p>					
					<hr>
                  @endif
                @endforeach
              @endif
            </div>
          </div>
        </div>
      </div>
	
    <div class="col-xs-12 no-padding">
		<p>
			Note : All of the above testimonial statements are genuine. However, the experiences of the customers who have submitted these testimonials are unique and do not guarantee or predict any outcome. These are few of the responses that we have received from Grocare India’s products users. You are welcome to share your experiences with us at info@grocare.com and we will publish them. 
		</p>
	</div>
  </div>

</div>

</div>

<!-- <div class="container-fluid">


<div class="row">

  <div class="col-md-12 no-padding">
  </div>

</div>

</div> -->


@endsection

@section('customJs')


@endsection

@extends('front.layout.tpl')

@section('customCss')



<style type="text/css">

  #searchContainer {

    margin-top: 0px !important;

  }

</style>





@endsection



@section('content')



<div class="container pageStart" style="margin-top:130px;">



<div class="row">



  <div class="col-md-5" id="searchContainer">





    <div id="custom-search-input">

                <div class="col-md-8 no-padding">

                    <input type="text" class="search-query form-control" placeholder="Search your ailment to find a Remedy" />

                </div>

                <div class="col-md-4 no-padding">

                  <button class="btn btn-default" type="button"> <span class=" glyphicon glyphicon-search"></span> &nbsp; &nbsp;SEARCH</button>

                </div>

    </div>



  </div>



  <div class="col-md-7" id="alphaList">

  <p>

    <?php

        for ($alpha='A'; $alpha!='AA'; $alpha++){ 

    ?>

        

        <a href="#">{{$alpha}}</a>



    <?php

        }   

    ?>

    </p>

  </div>



    

  </div>





  <div class="row" id="diagnosis">



    <div class="col-md-12 no-padding">





      <div class="col-md-3">



          <div class="diagnosisGrid">

            <a href="{{URL::to('self-diagnosis/acidim')}}">

            <div class="titleh3">ACNE</div>

            <p>Acne is a skin condition that consists of Pimples, Deep lumps and Plugged pores that primarily occur on the face.</p>

            </a>

          </div>



      </div>



      <div class="col-md-3">



          <div class="diagnosisGrid">

            <a href="{{URL::to('self-diagnosis/acidim')}}">

            <div class="titleh3">ACID REFLUX/ HEARTBURN</div>

            <p>Acid reflux is when some of the acid content of the stomach flows up into the esophagus, the food pipe...</p>

            </a>

          </div>



      </div>



      <div class="col-md-3">



          <div class="diagnosisGrid">

            <a href="{{URL::to('self-diagnosis/acidim')}}">

            <div class="titleh3">ACIDITY</div>

            <p>Heartburn, Regurgitation, Indigestion, Sour burps, Sore throat, Nausea, Chest pain, Dry persistent cough...</p>

            </a>

          </div>



      </div>



      <div class="col-md-3">



          <div class="diagnosisGrid">

            <a href="{{URL::to('self-diagnosis/acidim')}}">

            <div class="titleh3">AMOEBIASIS</div>

            <p>Amoebiasis is an infection caused by the protozoa called Entamoeba histolytica...</p>

            </a>

          </div>



      </div>



      <div class="col-md-3">



          <div class="diagnosisGrid">

            <a href="{{URL::to('self-diagnosis/acidim')}}">

            <div class="titleh3">ANEMIA</div>

            <p>Anemia occurs when there is not enough hemoglobin in the blood...</p>

            </a>

          </div>



      </div>



      <div class="col-md-3">



          <div class="diagnosisGrid">

            <a href="{{URL::to('self-diagnosis/acidim')}}">

            <div class="titleh3">ANKLE PAIN</div>

            <p>Ankle Pain is any type of pain or discomfort in the ankles. It may be accompanied with Stiffness...</p>

            </a>

          </div>



      </div>





      <div class="col-md-3">



          <div class="diagnosisGrid">

            <a href="{{URL::to('self-diagnosis/acidim')}}">

            <div class="titleh3">ARTHRITIS</div>

            <p>Arthritis is a condition in which there is Pain in joints, Stiffness, Inflammation, Swelling...</p>

            </a>

          </div>



      </div>



      <div class="col-md-3">



          <div class="diagnosisGrid">

            <a href="{{URL::to('self-diagnosis/acidim')}}">

            <div class="titleh3">CONSTIPATION</div>

            <p>Constipation is a condition in which person has difficulty in bowel movements...</p>

            </a>

          </div>



      </div>





      <div class="col-md-3">



          <div class="diagnosisGrid">

            <a href="{{URL::to('self-diagnosis/acidim')}}">

            <div class="titleh3">ACNE</div>

            <p>Acne is a skin condition that consists of Pimples, Deep lumps and Plugged pores that primarily occur on the face.</p>

            </a>

          </div>



      </div>



      <div class="col-md-3">



          <div class="diagnosisGrid">

            <a href="{{URL::to('self-diagnosis/acidim')}}">

            <div class="titleh3">ACID REFLUX/ HEARTBURN</div>

            <p>Acid reflux is when some of the acid content of the stomach flows up into the esophagus, the food pipe...</p>

            </a>

          </div>



      </div>



      <div class="col-md-3">



          <div class="diagnosisGrid">

            <a href="{{URL::to('self-diagnosis/acidim')}}">

            <div class="titleh3">ACIDITY</div>

            <p>Heartburn, Regurgitation, Indigestion, Sour burps, Sore throat, Nausea, Chest pain, Dry persistent cough...</p>

            </a>

          </div>



      </div>



      <div class="col-md-3">



          <div class="diagnosisGrid">

            <a href="{{URL::to('self-diagnosis/acidim')}}">

            <div class="titleh3">AMOEBIASIS</div>

            <p>Amoebiasis is an infection caused by the protozoa called Entamoeba histolytica...</p>

            </a>

          </div>



      </div>







    </div>





  </div>







</div>









@endsection



@section('customJs')





@endsection


@extends('layouts.app')
@section('title', 'FAQ')
@section('content')

    <div id="contact" class="section md-padding">
        <div class="container">
            <div class="row">
                <div class="section-header text-center">
                    <h2 class="title">FAQ</h2>
                    <h3>Frequently Asked Questions.</h3>
                </div>

                <div class="col-md-8 col-md-offset-2">
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                                       aria-expanded="true" aria-controls="collapseOne">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        What does Music Teacher's Aid do?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel"
                                 aria-labelledby="headingOne">
                                <div class="panel-body">
                                    Music Teachers Aid is software that aids private music teachers.
                                    The software keeps track of students, lesson, payments, gives feedback to
                                    parents, and automates transactions.
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingTwo">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"
                                       aria-expanded="true" aria-controls="collapseTwo">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        Can I cancel my subscription?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel"
                                 aria-labelledby="headingTwo">
                                <div class="panel-body">
                                    Yes of course you can cancel at anytime, though we hope you don't ever have to. The
                                    subscription process grants 30 days per payment cycle. If you
                                    signed on the 1st of the month and you cancel on the 15th your subscription is valid
                                    to the 30th of that month.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
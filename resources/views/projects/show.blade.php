{{--    @author Fabian Emanuel Pintea
        Bachelor's degree project ACS UPB 2018  --}}
@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('my_project', $project) }}
@endsection

@section('content')
<div class="container-fluid">
    <h2 class="text-center" style="margin-bottom: 20px;">{{ $project->title }}</h2>

	<div class="col-md-1"></div>
	<div class="col-md-10">

		<div class="well" style="background-color: white;">
			@if (isset($project->description) && $project->description != "")
				<div>{!! $project->description !!}</div>
			@else
				<div class="alert alert-danger">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					Proiectul nu are descriere !
				</div>
			@endif
			
			@if (isset($project->references) && $project->references != "")
				<h4>References</h4>
				<ul>
					@foreach ($project->references as $reference)
						<li>{!! $reference !!}</li>
					@endforeach
				</ul>
			@endif
		</div>

		<div id="comments">
			@foreach ($comments as $comment)
				@if ($comment->user_id == Auth::user()->id)
					<div class="comment-wrap">
						<div class="col-md-1"></div>
						<div class="col-md-11 comment-block">
							<div class="comment-name">{{ $comment->user->name }}<span class="comment-date">{{ $comment->comment_datetime }}</span></div>
							<div class="comment-text">{!! $comment->content !!}</div>
						</div>
					</div>
				@else
					<div class="comment-wrap">
						<div class="col-md-11 comment-block">
							<div class="comment-name">{{ $comment->user->name }}<span class="comment-date">{{ $comment->comment_datetime }}</span></div>
							<div class="comment-text">{!! $comment->content !!}</div>
						</div>
						<div class="col-md-1"></div>
					</div>
				@endif
			@endforeach
		</div>
		<div style="margin-top: 3%; margin-bottom: 3%;">
			<h4 style="white-space: nowrap;"><i class="fa fa-paper-plane-o"></i> Lasă un comentariu:</h4>
			{!! Form::model($project, ['method' => 'POST', 'route' => ['project.add_comment', $project->id], 'class' => 'form-horizontal']) !!}
				{!! Form::token() !!}

				{{ Form::textarea('content', '',
					['class' => 'form-control', 'placeholder' => 'Comentariul tău aici'])
				}}
				{{ Form::submit('Comenteză', ['class' => 'btn btn-primary', 'style' => 'margin-top: 1%;']) }}
			{!! Form::close() !!}
		</div>
	</div>
	<div class="col-md-1"></div>
</div>
@endsection